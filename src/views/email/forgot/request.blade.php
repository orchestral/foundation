<p>Hello {{ $user->fullname }},</p>

<p>
	We got a request to reset your password. If this was a mistake, just
	ignore this email and nothing will happen.
</p>

<p>
	To reset your password, please proceed to <a href="{{ $url }}">{{ $url }}</a> 
	and reset your password.
</p>
