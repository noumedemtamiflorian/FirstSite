<?php


namespace Tests\Framework;

use App\Framework\Upload;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;

require dirname(dirname(__DIR__)) . "/vendor/autoload.php";

class UploadTest extends TestCase
{
    /**
     * @var Upload
     */
    private Upload $upload;

    protected function setUp(): void
    {
        $this->upload = new Upload('tests');
    }

    public function tearDown(): void
    {
        if (file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'demo.jpg')) {
            unlink(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'demo.jpg');
        }
    }

    public function testUpload()
    {
        $uploadeFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();
        $uploadeFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo.jpg');
        $uploadeFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo('tests\demo.jpg'));
        $this->assertEquals('demo.jpg', $this->upload->upload($uploadeFile));
    }

    public function testUploadWithExistingFile()
    {
        $uploadeFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();
        $uploadeFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo_copy.jpg');
        touch(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'demo.jpg');
        $uploadeFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo('tests\demo_copy.jpg'));
        $this->assertEquals('demo_copy.jpg', $this->upload->upload($uploadeFile));
    }
}