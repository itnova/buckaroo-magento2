<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\Buckaroo\Test\Unit\Model\Method;

use Magento\Framework\DataObject;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Model\Order\Payment;
use TIG\Buckaroo\Gateway\Http\TransactionBuilder\Order;
use TIG\Buckaroo\Gateway\Http\TransactionBuilderFactory;
use TIG\Buckaroo\Model\ConfigProvider\Method\Factory;
use TIG\Buckaroo\Model\ConfigProvider\Method\PayPerEmail as PayPerEmailConfig;
use TIG\Buckaroo\Model\Method\PayPerEmail;
use TIG\Buckaroo\Test\BaseTest;

class PayPerEmailTest extends BaseTest
{
    protected $instanceClass = PayPerEmail::class;

    /**
     * @return array
     */
    public function assignDataProvider()
    {
        return [
            'no data' => [
                []
            ],
            'with skip validation data' => [
                [
                    'additional_data' => [
                        'buckaroo_skip_validation' => '1',
                    ]
                ]
            ],
            'with form data' => [
                [
                    'additional_data' => [
                        'customer_gender' => 'female',
                        'customer_billingFirstName' => 'TIG',
                        'customer_billingLastName' => 'TEST',
                        'customer_email' => '07/10/1990',
                    ]
                ]
            ],
        ];
    }

    /**
     * @param $data
     *
     * @dataProvider assignDataProvider
     */
    public function testAssignData($data)
    {
        $dataObject = $this->getObject(DataObject::class);
        $dataObject->addData($data);

        $instance = $this->getInstance();

        $infoInstanceMock = $this->getFakeMock(InfoInterface::class)->getMock();
        $instance->setData('info_instance', $infoInstanceMock);

        $result = $instance->assignData($dataObject);
        $this->assertInstanceOf(PayPerEmail::class, $result);
    }

    public function testGetOrderTransactionBuilder()
    {
        $payPerMailConfigMock = $this->getFakeMock(PayPerEmailConfig::class)->getMock();

        $factoryMock = $this->getFakeMock(Factory::class)->setMethods(['get'])->getMock();
        $factoryMock->expects($this->exactly(2))->method('get')->with('payperemail')->willReturn($payPerMailConfigMock);

        $infoInstanceMock = $this->getFakeMock(Payment::class)->setMethods(['getOrder'])->getMock();
        $infoInstanceMock->expects($this->once())->method('getOrder');

        $orderTransactionMock = $this->getFakeMock(Order::class)->setMethods(['setMethod'])->getMock();
        $orderTransactionMock->expects($this->once())->method('setMethod')->with('TransactionRequest');

        $transactionBuilderMock = $this->getFakeMock(TransactionBuilderFactory::class)->setMethods(['get'])->getMock();
        $transactionBuilderMock->expects($this->once())
            ->method('get')
            ->with('order')
            ->willReturn($orderTransactionMock);

        $instance = $this->getInstance([
            'configProviderMethodFactory' => $factoryMock,
            'transactionBuilderFactory' => $transactionBuilderMock
        ]);

        $result = $instance->getOrderTransactionBuilder($infoInstanceMock);
        $this->assertInstanceOf(Order::class, $result);

        $services = $result->getServices();
        $this->assertInternalType('array', $services);
        $this->assertEquals('payperemail', $services[0]['Name']);
        $this->assertEquals('PaymentInvitation', $services[0]['Action']);
        $this->assertEquals(1, $services[0]['Version']);
        $this->assertCount(6, $services[0]['RequestParameter']);

        $possibleParameters = ['customergender', 'CustomerEmail', 'CustomerFirstName', 'CustomerLastName', 'MerchantSendsEmail', 'PaymentMethodsAllowed'];

        foreach ($services[0]['RequestParameter'] as $array) {
            $this->assertArrayHasKey('_', $array);
            $this->assertArrayHasKey('Name', $array);
            $this->assertContains($array['Name'], $possibleParameters);
        }
    }

    public function testGetPayperemailService()
    {
        $payPerMailConfigMock = $this->getFakeMock(PayPerEmailConfig::class)
            ->setMethods(['getSendMail', 'getPaymentMethod'])
            ->getMock();
        $payPerMailConfigMock->expects($this->once())->method('getSendMail');
        $payPerMailConfigMock->expects($this->once())->method('getPaymentMethod');

        $factoryMock = $this->getFakeMock(Factory::class)->setMethods(['get'])->getMock();
        $factoryMock->expects($this->once())->method('get')->with('payperemail')->willReturn($payPerMailConfigMock);

        $infoInstanceMock = $this->getFakeMock(Payment::class)->setMethods(['getAdditionalInformation'])->getMock();
        $infoInstanceMock->expects($this->exactly(4))->method('getAdditionalInformation');

        $instance = $this->getInstance(['configProviderMethodFactory' => $factoryMock]);
        $result = $this->invokeArgs('getPayperemailService', [$infoInstanceMock], $instance);

        $this->assertInternalType('array', $result);
        $this->assertEquals('payperemail', $result['Name']);
        $this->assertEquals('PaymentInvitation', $result['Action']);
        $this->assertEquals(1, $result['Version']);
        $this->assertCount(6, $result['RequestParameter']);

        $possibleParameters = ['customergender', 'CustomerEmail', 'CustomerFirstName', 'CustomerLastName', 'MerchantSendsEmail', 'PaymentMethodsAllowed'];

        foreach ($result['RequestParameter'] as $array) {
            $this->assertArrayHasKey('_', $array);
            $this->assertArrayHasKey('Name', $array);
            $this->assertContains($array['Name'], $possibleParameters);
        }
    }

    public function testGetCmService()
    {
        $payPerMailConfigMock = $this->getFakeMock(PayPerEmailConfig::class)
            ->setMethods(['getSchemeKey'])
            ->getMock();
        $payPerMailConfigMock->expects($this->exactly(2))->method('getSchemeKey')->willReturn('abc');

        $factoryMock = $this->getFakeMock(Factory::class)->setMethods(['get'])->getMock();
        $factoryMock->expects($this->exactly(2))->method('get')->with('payperemail')->willReturn($payPerMailConfigMock);

        $addressMock = $this->getFakeMock(\Magento\Sales\Model\Order\Address::class)->getMock();

        $orderMock = $this->getFakeMock(\Magento\Sales\Model\Order::class)->setMethods(['getBillingAddress'])->getMock();
        $orderMock->method('getBillingAddress')->willReturn($addressMock);

        $infoInstanceMock = $this->getFakeMock(Payment::class)->setMethods(['getAdditionalInformation', 'getOrder'])->getMock();
        $infoInstanceMock->expects($this->once())->method('getAdditionalInformation');
        $infoInstanceMock->method('getOrder')->willReturn($orderMock);

        $instance = $this->getInstance(['configProviderMethodFactory' => $factoryMock]);
        $result = $this->invokeArgs('getCmService', [$infoInstanceMock], $instance);

        $this->assertInternalType('array', $result);
        $this->assertEquals('CreditManagement3', $result['Name']);
        $this->assertEquals('CreateCombinedInvoice', $result['Action']);
        $this->assertEquals(1, $result['Version']);
        $this->assertCount(20, $result['RequestParameter']);

        $possibleParameters = [
            'Code', 'Email', 'Mobile', 'InvoiceAmount', 'InvoiceAmountVAT', 'InvoiceDate', 'DueDate',
            'SchemeKey', 'MaxStepIndex', 'AllowedServices', 'AllowedServicesAfterDueDate', 'Culture',
            'FirstName', 'LastName', 'Gender', 'Street', 'HouseNumber', 'Zipcode', 'City', 'Country'
        ];

        foreach ($result['RequestParameter'] as $array) {
            $this->assertArrayHasKey('_', $array);
            $this->assertArrayHasKey('Name', $array);
            $this->assertContains($array['Name'], $possibleParameters);
        }
    }

    public function getCmAddressProvider()
    {
        return [
            'only street' => [
                'Kabelweg',
                [
                    'street'          => 'Kabelweg',
                    'house_number'    => '',
                    'number_addition' => '',
                ]
            ],
            'with house number' => [
                'Kabelweg 37',
                [
                    'street'          => 'Kabelweg',
                    'house_number'    => '37',
                    'number_addition' => '',
                ]
            ],
            'with house number addition' => [
                'Kabelweg 37A',
                [
                    'street'          => 'Kabelweg',
                    'house_number'    => '37',
                    'number_addition' => 'A',
                ]
            ],
            'as array' => [
                ['Kabel', 'Weg 37'],
                [
                    'street'          => 'Kabel Weg',
                    'house_number'    => '37',
                    'number_addition' => '',
                ]
            ],
        ];
    }

    /**
     * @param $address
     * @param $expected
     *
     * @dataProvider getCmAddressProvider
     */
    public function testGetCmAddress($address, $expected)
    {
        $instance = $this->getInstance();
        $result = $this->invokeArgs('getCmAddress', [$address], $instance);

        $this->assertEquals($expected, $result);
    }

    public function afterOrderProvider()
    {
        return [
            'no service' => [
                [],
                null
            ],
            'no invoicekey' => [
                [
                    (Object)[
                        'Name' => 'CreditManagement3',
                        'ResponseParameter' => (Object)[
                            'Name' => 'ResponseName',
                            '_' => 'abc'
                        ]
                    ]
                ],
                null
            ],
            'incorrect service' => [
                [
                    (Object)[
                        'Name' => 'PayPerEmail',
                        'ResponseParameter' => (Object)[
                            'Name' => 'InvoiceKey',
                            '_' => 'def'
                        ]
                    ]
                ],
                null
            ],
            'has invoicekey' => [
                [
                    (Object)[
                        'Name' => 'CreditManagement3',
                        'ResponseParameter' => (Object)[
                            'Name' => 'InvoiceKey',
                            '_' => 'ghi'
                        ]
                    ]
                ],
                'ghi'
            ],
        ];
    }

    /**
     * @param $service
     * @param $expected
     *
     * @dataProvider afterOrderProvider
     */
    public function testAfterOrder($service, $expected)
    {
        $infoInstanceMock = $this->getFakeMock(Payment::class)->setMethods(null)->getMock();

        $respone = [
            0 => (Object)[
                'Services' => (Object)[
                    'Service' => $service
                ]
            ]
        ];

        $instance = $this->getInstance();
        $result = $this->invokeArgs('afterOrder', [$infoInstanceMock, $respone], $instance);

        $this->assertInstanceOf(PayPerEmail::class, $result);
        $this->assertEquals($expected, $infoInstanceMock->getAdditionalInformation('buckaroo_cm3_invoice_key'));
    }

    public function testGetCaptureTransactionBuilder()
    {
        $infoInstanceMock = $this->getFakeMock(InfoInterface::class)->getMock();
        $instance = $this->getInstance();

        $result = $instance->getCaptureTransactionBuilder($infoInstanceMock);
        $this->assertFalse($result);
    }

    public function testGetAuthorizeTransactionBuilder()
    {
        $infoInstanceMock = $this->getFakeMock(InfoInterface::class)->getMock();
        $instance = $this->getInstance();

        $result = $instance->getAuthorizeTransactionBuilder($infoInstanceMock);
        $this->assertFalse($result);
    }

    public function testGetRefundTransactionBuilder()
    {
        $infoInstanceMock = $this->getFakeMock(InfoInterface::class)->getMock();
        $instance = $this->getInstance();

        $result = $instance->getRefundTransactionBuilder($infoInstanceMock);
        $this->assertFalse($result);
    }

    public function testGetVoidTransactionBuilder()
    {
        $infoInstanceMock = $this->getFakeMock(InfoInterface::class)->getMock();
        $instance = $this->getInstance();

        $result = $instance->getVoidTransactionBuilder($infoInstanceMock);
        $this->assertTrue($result);
    }
}
