var request = require('request');

request.post(
    'http://localhost:9876/fraudCheck',
    {
        json: {
            "uuid": "89c878e3-38f7-4831-af6c-c3b4a0669022",
            "name": "Fraud",
            "surname": "Fraudowski",
            "dateOfBirth": "2020-01-01",
            "gender": "FEMALE",
            "nationalIdentificationNumber": "1234567890"
        }
    },
    function (error, response, body) {
        console.log(response === undefined ? "undefined" : response.statusCode)
    }
);
