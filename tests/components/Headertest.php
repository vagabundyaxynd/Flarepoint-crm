  <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;
use App\Models\Department;

class Headertest extends TestCase
{
    use DatabaseTransactions;

    protected $client;
    protected $role;
    protected $faker;
    protected $user;
    protected $department;

    public function setup()
    {
        parent::setup();

        $this->faker = \Faker\Factory::create();

        //Create a user for loggin in without permissions
        $this->user = new User;
        $this->user->name = 'Casper';
        $this->user->email = 'bottelet@flarepoint.com';
        $this->user->address = $this->faker->address;
        $this->user->work_number = $this->faker->randomNumber(8);
        $this->user->personal_number = $this->faker->randomNumber(8);
        $this->user->password = bcrypt('admin');
        $this->user->save();

        //Create test client
        $this->client = New Client;
        $this->client->name = $this->faker->name;
        $this->client->company_name = $this->faker->company('name');
        $this->client->vat = $this->faker->randomNumber(8);
        $this->client->email = $this->faker->email;
        $this->client->address = $this->faker->address;
        $this->client->zipcode = $this->faker->postcode();
        $this->client->city = $this->faker->city;
        $this->client->primary_number = $this->faker->randomNumber(8);
        $this->client->secondary_number = $this->faker->randomNumber(8);
        $this->client->industry_id = $this->faker->numberBetween($min = 1, $max = 25);
        $this->client->fk_user_id = $this->user->id;
        $this->client->company_type = $this->faker->company('suffix');
        $this->client->save();
        //Create Role
        $this->role = new Role;
        $this->role->display_name = 'Test role';
        $this->role->name = 'Test Role';
        $this->role->description = 'Role for testing';
        $this->role->save();

        $newrole = new RoleUser;
        $newrole->role_id = $this->role->id;
        $newrole->user_id = $this->user->id;
        $newrole->timestamps = false;
        $newrole->save();

        $createTask = new PermissionRole;
        $createTask->role_id = $this->role->id;
        $createTask->permission_id = '7';
        $createTask->timestamps = false;
        $createTask->save();

        $createLead = new PermissionRole;
        $createLead->role_id = $this->role->id;
        $createLead->permission_id = '9';
        $createLead->timestamps = false;
        $createLead->save();

        $this->department = new Department;
        $this->department->name = 'Test Department';
        $this->department->save();

        \DB::table('department_user')->insert([
            'department_id' => $this->department->id,
            'user_id' => $this->user->id
        ]);


	}

    //This header is reused on Leads, Tasks, clients pages. 
	public function testPageHeader()
	{
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->visit('clients/' . $this->client->id)
            ->see($this->client->name)
            ->seeInElement('.contactleft', $this->client->email)
            ->seeInElement('.contactleft', $this->client->primary_number)
            ->seeInElement('.contactleft', $this->client->secondary_number)
            ->seeInElement('.contactleft', $this->client->address)
            ->seeInElement('.contactleft', $this->client->zipcode)
            ->seeInElement('.contactleft', $this->client->city)
            ->seeInElement('.contactright', $this->client->companyname)
            ->seeInElement('.contactright', $this->client->vat)
            ->seeInElement('.contactright', $this->client->company_type);
	}
}