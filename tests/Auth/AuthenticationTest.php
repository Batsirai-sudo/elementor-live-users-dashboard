<?php

namespace Tests\Auth;

use BatsiraiMuchareva\LiveUserDashboard\Repositories\UserRepository;
use BatsiraiMuchareva\LiveUserDashboard\Services\UserService;
use Tests\TestCase;
use Tests\Traits\HasReflectionHelper;

class AuthenticationTest extends TestCase
{
    use HasReflectionHelper;

    public function testSuccessfulAuthentication()
    {
        $repositoryMock = $this
            ->getMockBuilder(UserRepository::class)
            ->getMock();

        $userService = new UserService();

        // Inject the mock repository using the setRepository method
        $this->setClassProperty($userService, 'repository', $repositoryMock);

        $userData = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $repositoryMock->expects($this->once())
            ->method('update')
            ->willReturn(true);

        $repositoryMock->expects($this->once())
            ->method('find')
            ->with(['email' => 'john@example.com', 'name' => 'John Doe'])
            ->willReturn($userData);

        $result = $userService->authenticate([
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($userData, array_intersect_key($result, $userData));
    }

    public function testAuthenticationWithWrongCredentials()
    {
        // Mock UserRepository and Application
        $repositoryMock = $this->getMockBuilder(UserRepository::class)
            ->getMock();

        // Create an instance of UserService with mocked dependencies
        $userService = new UserService();
        $this->setClassProperty($userService, 'repository', $repositoryMock);

        // Mock data for testing
        $wrongCredentials = [
            'email' => 'wrong@example.com',
            'name' => 'Wrong User',
        ];

        // Set up expectations for the mock repository
        $repositoryMock->expects($this->once())
            ->method('find')
            ->with($wrongCredentials)
            ->willReturn(null); // Simulate no user found for wrong credentials

        // Call the method under test
        $result = $userService->authenticate($wrongCredentials);

        // Assert the result
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Invalid credentials to access the Dashboard', $result['error']);
    }
}