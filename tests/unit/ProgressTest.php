<?php

namespace tests\unit\models;

use app\models\Progress;

class ProgressTest extends \Codeception\Test\Unit
{
    public function testViewAllProgress()
    {
        $allProgress = Progress::find()->all();
        $this->assertIsArray($allProgress);
    }

    public function testViewProgressById()
    {
        $existingProgress = Progress::find()->one();

        if ($existingProgress) {
            $foundProgress = Progress::findOne($existingProgress->id);
            $this->assertNotNull($foundProgress);
            $this->assertEquals($existingProgress->id, $foundProgress->id);
        }
    }

    public function testViewProgressAttributes()
    {
        $progress = Progress::find()->one();

        if ($progress) {
            $this->assertNotNull($progress->id);
            $this->assertNotNull($progress->user);
            $this->assertNotNull($progress->module);
            $this->assertNotNull($progress->completed_at);
        }
    }

    public function testViewEmptyProgress()
    {
        $allProgress = Progress::find()->all();
        $this->assertIsArray($allProgress);
    }

    public function testViewNonExistentProgress()
    {
        $progress = Progress::findOne(999999);
        $this->assertNull($progress);
    }

    public function testViewProgressCount()
    {
        $totalCount = Progress::find()->count();
        $this->assertIsNumeric($totalCount);
    }
}