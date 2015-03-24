#{{ use Illuminate\Support\Fluent; }}
#{{ is_array($user) && $user = new Fluent($user) }}

Hello {{ $user->fullname }}

<p>Thank you for registering with us, in order to login please use the following:</p>

<p>E-mail Address: {{ $user->email }}</p>
<p>Password: {{ $password }}</p>
