 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;

class FormValidationErrorClientTest extends TestCase
{
    use DatabaseTransactions;

    protected $client;
    protected $role;
    protected $faker;

    public function setup()
    {
        parent::setup();

        $this->faker = \Faker\Factory::create();

        //Create a user for loggin in without permissions
        $user = new User;
        $user->name = 'Casper';
        $user->email = 'bottelet@flarepoint.com';
        $user->password = bcrypt('admin');
        $user->save();

        //Create test client
        $this->client = New Client;
        $this->client->name = $this->faker->name;
        $this->client->company_name = $this->faker->company('name');
        $this->client->email = $this->faker->email;
        $this->client->industry_id = $this->faker->numberBetween($min = 1, $max = 25);
        $this->client->fk_user_id = $user->id;
        $this->client->save();

        //Create Role
        $this->role = new Role;
        $this->role->display_name = 'Test role';
        $this->role->name = 'Test Role';
        $this->role->description = 'Role for testing';
        $this->role->save();

        $newrole = new RoleUser;
        $newrole->role_id = $this->role->id;
        $newrole->user_id = $user->id;
        $newrole->timestamps = false;
        $newrole->save();

        $createClient = new PermissionRole;
        $createClient->role_id = $this->role->id;
        $createClient->permission_id = '4';
        $createClient->timestamps = false;
        $createClient->save();


    }

    public function testMissingName()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('New Client')
            ->seePageIs('/clients/create')
            ->type($this->faker->email, 'email')
            ->type($this->faker->address, 'address')
            ->type($this->faker->company('name'), 'company_name')
            ->select($this->faker->numberBetween($min = 1, $max = 25), 'industry_id')
            ->select(1, 'fk_user_id')
            ->press('Create New Client')
            ->see('The name field is required.')
            ->seePageIs('/clients/create');
    }

    public function testMissingEmail()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('New Client')
            ->seePageIs('/clients/create')
            ->type($this->faker->name, 'name')
            ->type($this->faker->address, 'address')
            ->type($this->faker->company('name'), 'company_name')
            ->select($this->faker->numberBetween($min = 1, $max = 25), 'industry_id')
            ->select(1, 'fk_user_id')
            ->press('Create New Client')
            ->see('The email field is required.')
            ->seePageIs('/clients/create');
    }

    public function testMissingCompanyName()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('New Client')
            ->seePageIs('/clients/create')
            ->type($this->faker->name, 'name')
            ->type($this->faker->address, 'address')
            ->type($this->faker->email, 'email')
            ->select($this->faker->numberBetween($min = 1, $max = 25), 'industry_id')
            ->select(1, 'fk_user_id')
            ->press('Create New Client')
            ->see('The company name field is required.')
            ->seePageIs('/clients/create');
    }



}