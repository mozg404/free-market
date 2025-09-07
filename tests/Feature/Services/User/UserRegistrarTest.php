<?php

namespace Tests\Feature\Services\User;

use App\Exceptions\User\UserAlreadyRegisteredException;
use App\Models\User;
use App\Services\User\UserRegistrar;
use App\ValueObjects\Email;
use App\ValueObjects\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserRegistrarTest extends TestCase
{
    use RefreshDatabase;

    private UserRegistrar $userRegistrar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRegistrar = $this->app->make(UserRegistrar::class);
    }

    public function testSuccessRegister(): void
    {
        $name = 'Petr';
        $email = new Email('test@mail.ru');
        $plainPassword = '12345678';
        $password = new Password($plainPassword);

        $user = $this->userRegistrar->register($name, $email, $password);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email->value, $user->email);
        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email->value,
        ]);

        // Проверяем, что в бд записался правильный хэш пароля
        $this->assertTrue(
            Hash::check($plainPassword, User::find($user->id)->password)
        );
    }

    public function testDuplicate(): void
    {
        $email = 'duplicate@mail.ru';
        User::factory()->create(['email' => $email]);

        $this->expectException(UserAlreadyRegisteredException::class);
        $this->userRegistrar->register('Name', new Email($email), new Password('12345678'));
    }

    public function testEmptyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->userRegistrar->register('', new Email('test@mail.ru'), new Password('12345678'));
    }

    public function testNameShorterThen3Characters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->userRegistrar->register('xx', new Email('test@mail.ru'), new Password('12345678'));
    }

    public function testNameLongerThan255Characters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->userRegistrar->register(Str::random(256), new Email('test@mail.ru'), new Password('12345678'));
    }
}
