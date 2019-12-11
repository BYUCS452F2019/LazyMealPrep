<?php
require '../aws.phar';
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


class DbConnection
{
    private $dynamodb;
    private $marshaler;

    /**
     * DbConnection constructor.
     */
    public function __construct()
    {
        try {
            $sdk = new Aws\Sdk([
                'endpoint' => 'http://localhost:8000',
                'region' => 'us-west-2',
                'version' => 'latest'
            ]);
            $this->dynamodb  = $sdk->createDynamoDb();
            $this->marshaler = new Marshaler();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function put($table, $json){
        $params = [
            'TableName' => $table,
            'Item' => $this->marshaler->marshalJson($json)
        ];

        try {
            return $this->dynamodb->putItem($params);

        } catch (DynamoDbException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function get($table, $json){
        $params = [
            'TableName' => $table,
            'Item' => $this->marshaler->marshalJson($json)
        ];

        try {
            return $this->dynamodb->getItem($params);

        } catch (DynamoDbException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function delete($table, $json){
        $params = [
            'TableName' => $table,
            'Item' => $this->marshaler->marshalJson($json)
        ];

        try {
            return $this->dynamodb->deleteItem($params);

        } catch (DynamoDbException $e) {
            echo $e->getMessage() . "\n";
        }
    }
    public function update($table, $json){
        $params = [
            'TableName' => $table,
            'Item' => $this->marshaler->marshalJson($json)
        ];

        try {
            return $this->dynamodb->updateItem($params);

        } catch (DynamoDbException $e) {
            echo $e->getMessage() . "\n";
        }
    }

}