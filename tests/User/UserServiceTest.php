<?php

namespace Tests\User;

use BatsiraiMuchareva\LiveUserDashboard\Application;
use BatsiraiMuchareva\LiveUserDashboard\Repositories\UserRepository;
use BatsiraiMuchareva\LiveUserDashboard\Services\UserService;
use Exception;
use Tests\TestCase;
use Tests\Traits\HasReflectionHelper;

class UserServiceTest extends TestCase
{
    use HasReflectionHelper;

    public function testGetUsers()
    {
        $repositoryMock = $this
            ->getMockBuilder(UserRepository::class)
            ->getMock();

        $applicationMock = $this
            ->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userService = new UserService();
        $this->setClassProperty(
            $userService,
            'repository',
            $repositoryMock
        );
        $this->setClassProperty(
            $userService,
            'application',
            $applicationMock
        );

        $user1 = [
            'id' => 1,
            'name' => 'Test User 1',
            'email' => 'test-user1@example.com',
        ];

        $user2 = [
            'id' => 2,
            'name' => 'Test User 2',
            'email' => 'test-user2@example.com',
        ];

        $repositoryMock->expects($this->once())
            ->method('get')
            ->willReturn([$user1, $user2]);

        $result = $userService->getUsers();

        $this->assertEquals([$user1, $user2], $result);
    }

    public function testGetUser()
    {
        $repositoryMock = $this->getMockBuilder(UserRepository::class)
            ->getMock();

        $applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userService = new UserService();
        $this->setClassProperty($userService, 'repository', $repositoryMock);
        $this->setClassProperty($userService, 'application', $applicationMock);

        $user = [
            'id' => 123,
            'name' => 'Test User 1',
            'email' => 'test-user1@example.com',
        ];

        $repositoryMock->expects($this->once())
            ->method('findById')
            ->with($user['id'])
            ->willReturn($user);

        $result = $userService->getUser('123');

        $this->assertEquals($user, $result);
    }

    /**
     * @throws Exception
     */
    public function testPollingUsers()
    {
        $repositoryMock = $this->getMockBuilder(UserRepository::class)
            ->getMock();

        $userService = new UserService();
        $this->setClassProperty($userService, 'repository', $repositoryMock);

        $repositoryMock->expects($this->once())
            ->method('update')
            ->willReturn(true);

        $user = [
            'id' => 123,
            'name' => 'Test User 1',
            'email' => 'test-user1@example.com',
        ];

        $repositoryMock->expects($this->once())
            ->method('get')
            ->willReturn([$user]);

        $result = $userService->pollingUsers();

        $this->assertEquals([$user], $result);
    }
}