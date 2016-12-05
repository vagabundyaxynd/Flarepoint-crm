 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;

class FormValidationErrorTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');

        $this->createUser();
        $this->createClient();
        $this->createRole();
        $this->createTaskPermission();
    }

    public function testMissingTitle()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->click('New Task')
            ->seePageIs('/tasks/create')
            ->type($this->faker->text(30, 1), 'description')
            ->press('Create New Task')
            ->see('The title field is required.')
            ->seePageIs('/tasks/create');
    }

    public function testMissingDescription()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->click('New Task')
            ->seePageIs('/tasks/create')
            ->type($this->faker->title, 'title')
            ->press('Create New Task')
            ->see('The description field is required.')
            ->seePageIs('/tasks/create');
    }
}