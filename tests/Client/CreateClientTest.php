 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;

class CreateClientTest extends TestCase
{
    use DatabaseTransactions;


    protected $role;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');

        $this->createUser();
        $this->createRole();

    }

    public function testCanNotAccessCreatePageWithOutPermission()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->dontSee('New Client')
            ->visit('/clients/create')
            ->see('Not allowed to create client!')
            ->seePageIs('/clients');
    }

    public function testCanCreateClientWithPermission()
    {
        $this->createClientPermission();

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('New Client')
            ->seePageIs('/clients/create')
            ->type($this->faker->name, 'name')
            ->type($this->faker->email, 'email')
            ->type($this->faker->address, 'address')
            ->type($this->faker->randomNumber(8), 'vat')
            ->type($this->faker->company('name'), 'company_name')
            ->type($this->faker->randomNumber(4), 'zipcode')
            ->type($this->faker->city(), 'city')
            ->type($this->faker->randomNumber(8), 'primary_number')
            ->type($this->faker->randomNumber(8), 'secondary_number')
            ->type($this->faker->company('suffix'), 'company_type')
            ->select($this->faker->numberBetween($min = 1, $max = 25), 'industry_id')
            ->select(1, 'fk_user_id')
            ->press('Create New Client')
            ->see('Client successfully added')
            ->seePageIs('/clients');

    }
}