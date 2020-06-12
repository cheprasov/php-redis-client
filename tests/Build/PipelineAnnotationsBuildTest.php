<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <acheprasov84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\Build;

use PHPUnit\Framework\TestCase;

class PipelineAnnotationsBuildTest extends TestCase {

    /**
     * Update annotations for pipeline on tests
     */
    public function test_generate_annotations_for_pipeline() {
        chdir(__DIR__.'/../../');
        $result = `php ./tools/generate_annotations_for_pipeline.php update`;

        $lines = explode("\n", trim($result));
        $this->assertSame(6, count($lines));

        foreach ($lines as $line) {
            $this->assertSame(true, strpos($line, 'updated') > 0 || strpos($line, 'has not changes') > 0);
        }
    }

}
