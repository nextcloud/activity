<?php

declare(strict_types=1);

namespace OCA\Activity\Tests\Controller;

use OCA\Activity\Controller\APIv2Controller;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\SearchCriteria;
use OCA\Activity\TeamActivityScope;
use OCA\Activity\UserSettings;
use OCP\Activity\IManager;
use OCP\IRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TeamActivityResponseTest extends TestCase {
	public static function responses(): array {
		return [
			'empty personal feed' => [false, [], '', 304],
			'personal ETag match' => [false, [['activity_id' => 1]], 'etag', 304],
			'empty Team feed' => [true, [], '', 200],
			'Team ETag match' => [true, [], 'etag', 304],
		];
	}

	#[DataProvider('responses')]
	public function testResponseContract(bool $team, array $activities, string $etag, int $status): void {
		$controller = $this->getMockBuilder(APIv2Controller::class)
			->disableOriginalConstructor()
			->onlyMethods(['validateParameters', 'generateHeaders'])
			->getMock();
		$controller->method('generateHeaders')->willReturn(['ETag' => 'etag']);
		$request = $this->createMock(IRequest::class);
		$request->expects(!$team && $activities === [] ? $this->never() : $this->once())
			->method('getHeader')->with('If-None-Match')->willReturn($etag);
		$data = $this->createMock(Data::class);
		$data->expects($this->once())->method($team ? 'getTeam' : 'get')->willReturn([
			'data' => $activities, 'headers' => [], 'has_more' => false,
		]);
		foreach ([
			'request' => $request,
			'activityManager' => $this->createMock(IManager::class),
			'data' => $data,
			'helper' => $this->createMock(GroupHelper::class),
			'settings' => $this->createMock(UserSettings::class),
			'searchCriteria' => SearchCriteria::empty(),
		] as $property => $value) {
			(new \ReflectionProperty(APIv2Controller::class, $property))->setValue($controller, $value);
		}
		$response = (new \ReflectionMethod(APIv2Controller::class, 'get'))->invoke(
			$controller, 'all', 0, 50, false, '', 0, 'desc', '', 0, 0, '', $team ? new TeamActivityScope(42, []) : null,
		);
		$this->assertSame($status, $response->getStatus());
		$this->assertSame([], $response->getData());
	}
}
