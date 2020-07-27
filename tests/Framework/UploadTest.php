<?php


namespace Tests\Framework;

use App\Framework\Upload;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;


class UploadTest extends TestCase
{
    /**
     * @var Upload
     */
    private $upload;
    /**
     * @var string
     */
    private $defaultPath;

    protected function setUp(): void
    {
        $this->defaultPath = __DIR__ . DIRECTORY_SEPARATOR . "image";
        $this->upload = new Upload($this->defaultPath);
    }

    public function tearDown(): void
    {
        if (file_exists($this->defaultPath . '/demo.jpg')) {
            unlink($this->defaultPath . '/demo.jpg');
        }
    }

    public function testUpload()
    {
        $uploadeFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();
        $uploadeFile->expects($this->any())
            ->method('getError')
            ->willReturn(0);
        $uploadeFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo.jpg');
        $uploadeFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo($this->defaultPath . DIRECTORY_SEPARATOR . 'demo.jpg'));
        $this->assertEquals("demo.jpg", $this->upload->upload($uploadeFile));
    }

    public function testUploadWithExistingFile()
    {
        $uploadeFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();
        $uploadeFile->expects($this->any())
            ->method('getError')
            ->willReturn(0);
        $uploadeFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo_copy.jpg');
        touch($this->defaultPath . DIRECTORY_SEPARATOR.'demo.jpg');
        $uploadeFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo($this->defaultPath . DIRECTORY_SEPARATOR.'demo_copy.jpg'));
        $this->assertEquals("demo_copy.jpg", $this->upload->upload($uploadeFile));
    }
}