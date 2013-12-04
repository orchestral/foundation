<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Presenter\User;
use Orchestra\Model\User as Eloquent;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Container;

        $this->app['app'] = $this->app;
        $this->app['orchestra.app'] = m::mock('OrchestraApplication')->shouldDeferMissing();
        $this->app['translator'] = m::mock('Translator');

        $this->app['orchestra.app']->shouldReceive('handles');
        $this->app['translator']->shouldReceive('trans');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Presenter\User::table() method.
     *
     * @test
     */
    public function testTableMethod()
    {
        $app    = $this->app;
        $model  = new Fluent;
        $table  = m::mock('TableBuilder');
        $column = m::mock('TableColumnBuilder');
        $value  = (object) array(
            'fullname' => 'Foo',
            'roles'    => array(
                (object) array('id' => 1, 'name' => 'Administrator'),
                (object) array('id' => 2, 'name' => 'Member'),
            ),
        );

        $stub = new User;

        $column->shouldReceive('label')->twice()->andReturn(null)
            ->shouldReceive('escape')->once()->with(false)->andReturn(null)
            ->shouldReceive('value')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($value) {
                    $c($value);
                });
        $table->shouldReceive('with')->once()->with($model, true)->andReturn(null)
            ->shouldReceive('layout')->once()->with('orchestra/foundation::components.table')->andReturn(null)
            ->shouldReceive('column')->once()->with('fullname', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) use ($column) {
                    $c($column);
                })
            ->shouldReceive('column')->once()->with('email', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) use ($column) {
                    $c($column);
                });

        $app['orchestra.table'] = m::mock('TableEnvironment');
        $app['html'] = m::mock('HtmlBuilder');

        $app['orchestra.table']->shouldReceive('of')->once()
                ->with('orchestra.users', m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($table) {
                    $c($table);
                    return 'foo';
                });
        $app['html']->shouldReceive('create')->once()
                ->with('span', 'Administrator', m::any())->andReturn('administrator')
            ->shouldReceive('create')->once()
                ->with('span', 'Member', m::any())->andReturn('member')
            ->shouldReceive('create')->once()
                ->with('strong', 'Foo')->andReturn('Foo')
            ->shouldReceive('create')->once()->with('br')->andReturn('')
            ->shouldReceive('create')->once()->with('span', 'raw-foo', m::any())->andReturn(null)
            ->shouldReceive('raw')->once()->with('administrator member')->andReturn('raw-foo');

        $this->assertEquals('foo', $stub->table($model));
    }

    /**
     * Test Orchestra\Foundation\Presenter\User::actions()
     * method.
     *
     * @test
     */
    public function testActionsMethod()
    {
        $app    = $this->app;
        $table  = m::mock('\Orchestra\Html\Table\TableBuilder');
        $grid   = m::mock('TableGridBuilder');
        $column = m::mock('TableColumnBuilder');
        $value  = (object) array(
            'id'   => 1,
            'name' => 'Foo',
        );

        $stub = new User;

        $column->shouldReceive('label')->once()->with('')->andReturn(null)
            ->shouldReceive('escape')->once()->with(false)->andReturn(null)
            ->shouldReceive('headers')->once()->with(m::type('Array'))->andReturn(null)
            ->shouldReceive('value')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($value) {
                    $c($value);
                });
        $grid->shouldReceive('column')->once()->with('action', m::type('Closure'))
            ->andReturnUsing(function ($n, $c) use ($column) {
                $c($column);
            });

        $table->shouldReceive('extend')->once()->with(m::type('Closure'))
            ->andReturnUsing(function ($c) use ($grid) {
                $c($grid);
                return 'foo';
            });

        $app['auth'] = m::mock('\Illuminate\Auth\Guard')->shouldDeferMissing();
        $app['html'] = m::mock('\Orchestra\Html\HtmlBuilder')->shouldDeferMissing();

        $app['auth']->shouldReceive('user')->once()->andReturn((object) array('id' => 2));
        $app['html']->shouldReceive('link')->once()
                ->with(handles("orchestra/foundation::users/1/edit"), m::any(), m::type('Array'))
                ->andReturn('edit')
            ->shouldReceive('link')->once()
                ->with(handles("orchestra/foundation::users/1/delete"), m::any(), m::type('Array'))
                ->andReturn('delete')
            ->shouldReceive('raw')->once()->with('editdelete')->andReturn('raw-edit')
            ->shouldReceive('create')->once()
                ->with('div', 'raw-edit', m::type('Array'))->andReturn('create-div');

        $this->assertEquals('foo', $stub->actions($table));
    }

    /**
     * Test Orchestra\Foundation\Presenter\User::form() method.
     *
     * @test
     */
    public function testFormMethod()
    {
        $app      = $this->app;
        $model    = m::mock('\Orchestra\Model\User');
        $form     = m::mock('FormBuilder');
        $fieldset = m::mock('FormFieldsetBuilder');
        $control  = m::mock('FormControlBuilder');
        $value    = (object) array(
            'roles' => new Collection(array(
                new Fluent(array('id' => 1, 'name' => 'Administrator')),
                new Fluent(array('id' => 2, 'name' => 'Member')),
            )),
        );

        $model->shouldReceive('hasGetMutator')->andReturn(false);

        $stub = new User;

        $control->shouldReceive('label')->times(4)->andReturn(null)
            ->shouldReceive('options')->once()->with('roles')->andReturn(null)
            ->shouldReceive('attributes')->once()->with(m::type('Array'))->andReturn(null)
            ->shouldReceive('value')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($value) {
                    $c($value);
                });
        $fieldset->shouldReceive('control')->twice()->with('input:text', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                })
            ->shouldReceive('control')->once()->with('input:password', 'password', m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                })
            ->shouldReceive('control')->once()->with('select', 'roles[]', m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                });
        $form->shouldReceive('resource')->once()
                ->with($stub, 'orchestra/foundation::users', $model)->andReturn(null)
            ->shouldReceive('hidden')->once()->with('id')->andReturn(null)
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        $app['orchestra.role'] = m::mock('\Orchestra\Model\Role')->shouldDeferMissing();
        $app['orchestra.form'] = m::mock('FormEnvironment');
        $app['orchestra.form.control'] = $control;

        $app['orchestra.role']->shouldReceive('lists')->once()
                ->with('name', 'id')->andReturn('roles');
        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.users', m::any())
                ->andReturnUsing(function ($f, $c) use ($form) {
                    $c($form);
                    return 'foo';
                });

        $stub->form($model);
    }
}
