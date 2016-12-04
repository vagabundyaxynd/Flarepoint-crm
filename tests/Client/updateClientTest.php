 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;

class UpdateClientTest extends TestCase
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


    }

    public function testCanNotAccessUpdatePageWithOutPermission()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('All Clients')
            ->dontSee('Edit')
            ->visit('clients/' . $this->client->id . '/edit')
            ->see('Not allowed to update client');
    }

    public function testCanUpdateClient()
    {
        //give permission to role
        $updateClient = new PermissionRole;
        $updateClient->role_id = $this->role->id;
        $updateClient->permission_id = '5';
        $updateClient->timestamps = false;
        $updateClient->save();

         $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('All Clients')
            ->see('Edit')
            ->visit('clients/' . $this->client->id . '/edit')
            ->type($this->faker->name, 'name')
            ->type($this->faker->email, 'email')
            ->type($this->faker->address, 'address')
            ->press('Update client');

        //Assert that the informtion has actully updated
        $this->assertNotEquals($this->client->name, $this->client->fresh()->name);
        $this->assertNotEquals($this->client->address, $this->client->fresh()->address);
        $this->assertNotEquals($this->client->email, $this->client->fresh()->email);
    }



}