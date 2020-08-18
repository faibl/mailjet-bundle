<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Psr\Log\LoggerInterface;

class MailjetServiceTest extends FaiblMailjetBundleTestCase
{
    public function testStandardConfig()
    {
//        $this->bootFaiblMailjetBundleKernel();
//
//        $mail = (new MailjetTemplateMail(123))
//            ->addReceiver((new MailjetAddress('receiver@mail.de')));
//
//        $mailjetService = $this->getContainer()->get(MailjetService::class);
//
//        $success = $mailjetService->send($mail);



//        $mock = $this->getMockBuilder(MailjetService::class)
//            ->disableOriginalConstructor()
////            ->setConstructorArgs([$this->getService(MailjetMailSerializer::class), $this->getService('logger'), 'key', 'secret', 'v3.1', true])
//            ->onlyMethods(['sendMail'])
//            ->getMock();
//
//        $mock->expects($this->once())
//            ->method('sendMail')
//            ->with($this->identicalTo($mail));
//
//        $mock->send($mail);
    }

//    public function testDisabledConfig()
//    {
//        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../config/faibl_mailjet.yaml');
//
//        $mail = (new MailjetTemplateMail(123))
//            ->addReceiver((new MailjetAddress('receiver@mail.de')));
//
//        $observer = $this->createMock(MailjetService::class);
//
//        $observer->send($mail);
//        $observer->expects($this->once())
//            ->method('sendMail');
//
//    }
 }
