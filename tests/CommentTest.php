<?php

/**
 * This file is part of the Nginx Config Processor package.
 *
 * (c) Toms Seisums
 * (c) Roman Piták <roman@pitak.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests;

use Nfigurator\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{

    public function testGetText()
    {
        $comment = new Comment('c');
        $this->assertEquals("c", $comment->getText());
    }

    public function testToString()
    {
        $comment = new Comment('c');
        $this->assertEquals("# c\n", (string) $comment);
    }

}
