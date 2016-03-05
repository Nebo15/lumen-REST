<?php

class {TestName}Cest
{
    private $api_prefix = '{api_prefix}';

    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }
    
    public function createOk(ApiTester $I)
    {
    
    }
    
    public function createInvalid(ApiTester $I)
    {
    
    }
    
    public function updateOk(ApiTester $I)
    {
    
    }
    
    public function updateInvalid(ApiTester $I)
    {
    
    }
    
    public function copy(ApiTester $I)
    {
    
    }
    
    public function delete(ApiTester $I)
    {
        $this->createGroup($I);
        $this->createGroup($I);
        
        $I->sendGET($this->api_prefix . '/{route}');
        $this->assertListGroup($I);
        
        $response = $I->getResponseFields();
        $id1 = $response->data[0]->_id;
        $id2 = $response->data[1]->_id;
        $I->sendDELETE($this->api_prefix . '/{route}/' . $id1);
        
        $I->sendGET($this->api_prefix . '/{route}/' . $id1);
        $I->seeResponseCodeIs(404);
        
        $I->sendGET($this->api_prefix . '/{route}/' . $id2);
        $I->assertTable();
    }
    
    private function createGroup(ApiTester $I)
    {
        $I->sendPOST($this->api_prefix . '/{route}', [
            {create_data}
        ]);
        $this->assertGroup($I, '$.data', 201);
    
        return json_decode($I->grabResponse())->data;
    }
    
    private function assertGroup(ApiTester $I, $jsonPath = '$.data', $code = 200)
    {
        $I->seeResponseCodeIs($code);
        $I->seeResponseMatchesJsonType([
            '_id' => 'string',
            {create_data}
        ], $jsonPath);
    }
    
    private function assertListGroup(ApiTester $I, $jsonPath = '$.data[*]')
    {
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            '_id' => 'string',
            {create_data}
        ], $jsonPath);
    }
}
