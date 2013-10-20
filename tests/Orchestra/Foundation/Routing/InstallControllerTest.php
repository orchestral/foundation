<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Orchestra\Foundation\Services\TestCase;

class InstallControllerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test GET /admin/install
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $dbConfig = array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        );

        $requirement = m::mock('\Orchestra\Foundation\Installation\RequirementInterface');
        $requirement->shouldReceive('check')->once()->andReturn(true)
            ->shouldReceive('getChecklist')->once()->andReturn(array(
                'databaseConnection' => array(
                    'is'       => true,
                    'should'   => true,
                    'explicit' => true,
                    'data'     => array(),
                ),
            ));
        $user = m::mock('UserModel', '\Orchestra\Model\User');
        App::bind('UserModel', function () use ($user) {
            return $user;
        });
        App::bind('Orchestra\Foundation\Installation\RequirementInterface', function () use ($requirement) {
            return $requirement;
        });
        Config::set('database.default', 'mysql');
        Config::set('auth', array('driver' => 'eloquent', 'model' => 'UserModel'));
        Config::set('database.connections.mysql', $dbConfig);

        $this->call('GET', 'admin/install');
        $this->assertResponseOk();
        $this->assertViewHasAll(array(
            'database',
            'auth',
            'authentication',
            'installable',
            'checklist'
        ));
    }

    /**
     * Test GET /admin/install/prepare
     *
     * @test
     */
    public function testGetPrepareAction()
    {
        $installer = m::mock('\Orchestra\Foundation\Installation\InstallerInterface');
        $installer->shouldReceive('migrate')->once()->andReturn(null);

        App::bind('Orchestra\Foundation\Installation\InstallerInterface', function () use ($installer) {
            return $installer;
        });

        $this->call('GET', 'admin/install/prepare');
        $this->assertRedirectedTo(handles('orchestra::install/create'));
    }

    /**
     * Test GET /admin/install/create
     *
     * @test
     */
    public function testGetCreateAction()
    {
        $this->call('GET', 'admin/install/create');
        $this->assertResponseOk();
        $this->assertViewHas('siteName', 'Orchestra Platform');
    }

    /**
     * Test GET /admin/install/create
     *
     * @test
     */
    public function testPostCreateAction()
    {
        $input = array();
        $installer = m::mock('\Orchestra\Foundation\Installation\InstallerInterface');
        $installer->shouldReceive('createAdmin')->once()->with($input)->andReturn(true);
        App::bind('Orchestra\Foundation\Installation\InstallerInterface', function () use ($installer) {
            return $installer;
        });

        $this->call('POST', 'admin/install/create', $input);
        $this->assertRedirectedTo(handles('orchestra::install/done'));
    }

    /**
     * Test GET /admin/install/create when create admin failed.
     *
     * @test
     */
    public function testPostCreateActionWhenCreateAdminFailed()
    {
        $input = array();
        $installer = m::mock('\Orchestra\Foundation\Installation\InstallerInterface');
        $installer->shouldReceive('createAdmin')->once()->with($input)->andReturn(false);
        App::bind('Orchestra\Foundation\Installation\InstallerInterface', function () use ($installer) {
            return $installer;
        });

        $this->call('POST', 'admin/install/create', $input);
        $this->assertRedirectedTo(handles('orchestra::install/create'));
    }

    /**
     * Test GET /admin/install/done
     *
     * @test
     */
    public function testGetDoneAction()
    {
        $this->call('GET', 'admin/install/done');
        $this->assertResponseOk();
    }
}
