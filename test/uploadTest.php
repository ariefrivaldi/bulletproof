<?php

class uploadTest extends \PHPUnit_Framework_TestCase
{
    public $bulletproof,
        $testingImage,
        $image = [];

    /**
     *  Initialize an array to mimic the properties $_FILES global
     */
    public function __construct()
    {
        $image = [
            'ikea' => [
                'name' => $this->testingImage = __DIR__ . "/monkey.jpg",
                'type' => 'image/jpg',
                'tmp_name' => $this->testingImage = __DIR__ . "/monkey.jpg",
                'error' => 0,
                'size' => 17438,
            ]
        ];

        $this->bulletproof = new \BulletProofTest\BulletProofOverride($image);

    }


    /**
     * test array access offset name is created from $_FILES
     */
    public function testArrayAccessReadsFileNameFromArray()
    {
        $this->assertEquals($this->bulletproof['ikea'], true);
    }

    /**
     * test custom image renaming
     */
    public function testImageRenameReturnsNewName()
    {
        $this->bulletproof->setName('foo');
        $this->assertEquals($this->bulletproof->getName(), 'foo');
    }

    /**
     * test storage creation
     */
    public function testImageLocationReturnsAssignedValue()
    {
        $this->bulletproof->setLocation('family_pics');
        $this->assertEquals($this->bulletproof->getLocation(), 'family_pics');
    }

    /**
     * test upload fails if image is less than the size set
     */
    public function testUploadFailsIfImageSizeIsSmallerThanDefined()
    {
        $this->bulletproof['ikea'];
        $this->bulletproof->setSize(100, 1000);
        $upload = $this->bulletproof->upload();
        $this->assertEquals(
            $this->bulletproof['error'],
            "Image size should be less than 1 kb"
        );
    }

    /**
     * test image is uploaded based on the mime types set
     */
    public function testImageUploadAcceptsOnlyAllowedMimeTypes()
    {
        $this->bulletproof['ikea'];
        $this->bulletproof->setMime(["png"]);
        $upload = $this->bulletproof->upload();
        $this->assertEquals(
            $this->bulletproof["error"],
            "Invalid File! Only (png) image types are allowed");
    }

    /**
     * test image upload does not pass the defined height limit
     */
    public function testImageDimensionDefinesImageHeightAndWidthLimit()
    {
        $this->bulletproof['ikea'];
        $this->bulletproof->setDimension(100, 200);
        $upload = $this->bulletproof->upload();
        $this->assertEquals(
            $this->bulletproof["error"],
            "Image height should be less than 100 pixels."
        );

    }

    /**
     * test image name has auto-generated value if name is not provided
     */
    public function testReturnValueOfImageNameAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->upload();
        $this->assertSame(strlen($upload->getName()), 28);
    }

    /**
     * test image size return
     */
    public function testReturnValueOfImageSizeAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->upload();
        $this->assertSame($upload->getSize(), 17438);
    }

    /**
     * test image mime return
     */
    public function testReturnValueOfImageMimeAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->upload();
        $this->assertSame($upload->getMime(), 'jpeg');
    }

    /**
     * test image width return
     */
    public function testReturnValueOfImageWidthAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->upload();
        $this->assertSame($upload->getWidth(), 384);
    }

    /**
     * test image height return
     */
    public function testReturnValueOfImageHeightAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->upload();
        $this->assertSame($upload->getHeight(), 345);
    }

    /**
     * test image location return
     */
    public function testReturnValueOfImageLocationAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $this->bulletproof->setLocation('samayo');
        $upload = $this->bulletproof->upload();
        $this->assertSame($upload->getLocation(), 'samayo');
    }

    /**
     * test image full path return
     */
    public function testReturnValueOfImageFullPathAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $this->bulletproof->setLocation('passport');
        $this->bulletproof->setName('2012');
        $upload = $this->bulletproof->upload();
        $getMime = $this->bulletproof->getMime();
        $this->assertSame($upload->getFullPath(), 'passport/2012.' . $getMime);
    }

    /**
     * test image json value return
     */
    public function testReturnValueOfImageJsonInfoAfterImageUpload()
    {
        $this->bulletproof['ikea'];
        $upload = $this->bulletproof->setName('we_belive_in_json')->upload();
        $this->assertSame($upload->getJson(), '{"name":"we_belive_in_json","mime":"jpeg","height":345,"width":384,"size":17438,"location":"images"}');

    }


}

