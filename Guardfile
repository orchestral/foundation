guard :phpunit, :all_on_start => false, :tests_path => 'tests/', :cli => '--colors -c build/phpunit.xml' do
	# Run any test in app/tests upon save.
	watch(%r{^.+Test\.php$})

	# When a file is edited, try to run its associated test.
	# Save app/models/User.php, and it will run app/tests/models/UserTest.php
	watch(%r{^src/(.+)/(.+)\.php$}) { |m| "tests/#{m[1]}/#{m[2]}Test.php"}
end
