<?php

$personas = array();
$identities = array();
$claims = array();
$users = array();

$personas[] = "
{
  \"_id\": \"5656dcaad7036cdc100041ba\",
  \"first_name\": \"Aleksandr2\",
  \"last_name\": \"Gorelik2\",
  \"birthdate\": {
    \"value\": \"07/05/1987\",
    \"format\": \"mm/dd/yyyy\"
  },
  \"email\": \"a.a.gorelik@gmail.com\",
  \"phone\": \"+79162194530\",
  \"passports\": [
    {
      \"number\": \"77 1234567\",
      \"nationality\": {
        \"value\": \"USA\",
        \"format\": \"alpha-3\"
      }
    }
  ],
  \"flight\": \"NA-309\"
}";

$personas[] = "
{
  \"_id\": \"5656e750d7036cdb100041c9\",
  \"first_name\": \"Aleksandr\",
  \"last_name\": \"Gorelik\",
  \"birthdate\": {
    \"value\": \"07/05/1987\",
    \"format\": \"mm/dd/yyyy\"
  },
  \"email\": \"aagorelik@gmail.com\",
  \"phone\": \"+79152194530\",
  \"passports\": [
    {
      \"number\": \"77 1234567111\",
      \"nationality\": {
        \"value\": \"USA\",
        \"format\": \"alpha-3\"
      }
    }
  ],
  \"flight\": \"NA-308\"
}";

$personas[] = "
{
  \"_id\": \"5660c501c6206e0a1b5b52b1\",
  \"first_name\": \"Aleksandr\",
  \"last_name\": \"Gorelik\",
  \"birthdate\": {
    \"value\": \"07/05/1987\",
    \"format\": \"mm/dd/yyyy\"
  },
  \"email\": \"a.a.gorelik@gmail.com\",
  \"phone\": \"+79162194530\",
  \"passports\": [
    {
      \"number\": \"77 1234567\",
      \"nationality\": {
        \"value\": \"USA\",
        \"format\": \"alpha-3\"
      }
    }
  ],
  \"flight\": \"NA-310\"
}";

$identities[] = "
{
  \"_id\": \"5656da84d7036cdd100041c7\",
  \"first_name\": \"913e07c5edfb5e4b5974c940ba44fd2ab56e85ea926e96dc2b61f554bb342cb2ccc05d71f5e80c7adae9549c3d6c03f0aa1a0d5e5424b63b40268df9dbed73ea\",
  \"last_name\": \"224f5248049cd1db7f43998581b67dde4d1ed9cf2a6407490fdf8c8a9a09bf409bb69b715514dd55d53f8226ef9819e0e30512eeddb2056dd08259c4c666965e\",
  \"birthdate\": \"16dec73c1e6227fa5d1835093caf04ec290bbf91a1d4ffa276e305a95b240d7dd2357ab4c936eeb71143cc764a7cc6dca165d8512d856203ab47b750c816cabc\",
  \"email\": \"249273d4fa52fb6ee4972e9b1c737ebc9d82592ecb96bea6f6ac6b70e0d9710f91c2fea67b97f903267fdd25b08d1996fa0235f5850487eb42f1626f8d276537\",
  \"phone\": \"3287d03c6d7116be9dc68a647b259e56bc758f043517f274bc2713cf38aa761e1b147d684e40c0ee16b9a60760df53c5039da597315f9903d1e3258290262f83\",
  \"passports\": [
    {
      \"number\": \"f39d700baeda036e02df57c4f6830a3176d5a57ee513c107be98567c8f2b5abf38ce5813b4fe264b74ef68472495c3d69af25ce7240819fdae27db936cbb36f1\",
      \"nationality\": \"8e86b6e6736b6171e8f1e3d0d9166b62954707e1b8ac4ca058f4046d2d37c990fae3e62d2daf1f712263094788255c86951cbda70f98e740eb896a886f17c311\"
    }
  ],
  \"created\": 1448532612,
  \"history\": [
    {
      \"time\": 1448532612,
      \"action\": \"created\",
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    },
    {
      \"time\": 1448532612,
      \"action\": \"used\",
      \"cause\": \"buying a ticket\",
      \"fields\": {
        \"first_name\": 1,
        \"last_name\": 1,
        \"birthdate\": 1,
        \"email\": 1,
        \"phone\": 1,
        \"passports\": [
          {
            \"number\": 1,
            \"nationality\": 1
          }
        ]
      },
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    },
    {
      \"time\": 1448532613,
      \"action\": \"check\",
      \"fields\": {
        \"first_name\": 1,
        \"last_name\": 1,
        \"birthdate\": 1,
        \"email\": 1,
        \"phone\": 1,
        \"passports\": [
          {
            \"number\": 1,
            \"nationality\": 1
          }
        ]
      },
      \"diff_fields\": [

      ],
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    },
    {
      \"time\": 1448532616,
      \"action\": \"verification\",
      \"fields\": {
        \"birthdate\": 1,
        \"first_name\": 1,
        \"last_name\": 1,
        \"passports\": [
          {
            \"number\": 1,
            \"nationality\": 1
          }
        ]
      },
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    }
  ],
  \"verifications\": {
    \"first_name\": [
      {
        \"source_id\": \"5656d8bfd7036ce6320041a7\",
        \"time\": 1448532616,
        \"signature\": \"Nq1Yo+Z67wCZlFJNJVoIp/sjUyvU/eYMB/37Ur3lKJkiauilrIHZhmk3kUFCvTQ5a8fyXp2U3py+wXYXkWoTmRYGWtWaR65e8t59RRsDKtIoIlP9S4GOLNb6tXwSMxV31eZfe95nY+0M0QzEjipnkU/f3+U2tpv+e1CrBFAp8lkG0LDa3LwlIrDbYpNducRMRFDadyfKg3LM3R4ZutcWAJk4qLDm4hEhXYYv/W/FJ7JrWMDhPkR3h62ih7gUCVoJBdZH3xnEdf/VmxqVvrntc0+bGKqkketUiLNjrDeJYKjJzpTaLVT8u5oJn91OCK2rFfY4LU0GvG0Q2bsgK7lc+E3Lw5fZ5DgeY2CkjjfxNb7Q/tdC44wSqbRj7Vzk4qpUCaukUM4B0WTNOnjYw2eltPTdnUJ009blD6QIneYsilBY/kAWyYNNXIs4y67IV8mbhNL+2epceX/SVBTbaBOReyJPGD9nIFHEp0WWpBN2eYIq2WAFlEnAiUnYkqpA4EopOovs7YYUEUVq7nGQasI5rouubLeFIG27FW2LAGS7twgMARu2qSen51r5ZKu5YtBnTwiLo5BSy7vYm++FhsdaiMNvO/GqDjrahvJxoo/cbjpnsvt5AZ9q9iz5F3z3ewonAWyIaqk2gy7I8GIIiMq/TbpbIzFi1F0nivj74MXZCgQ=\"
      }
    ],
    \"last_name\": [
      {
        \"source_id\": \"5656d8bfd7036ce6320041a7\",
        \"time\": 1448532616,
        \"signature\": \"f3JYJccELwc5pnklUJaWwkR+ccZfOXaHcDlOpKEJiUZw/5vnfn8lbtg6N8WWAL6educCdq/bGab/u9cMRIzu/4MLZe2NYyK9Pl09fjBxvYoxpEFi88aw3JrGmH8/NCLVx9C1n+a6DF/sVAiNxpPxFTf/ps7ORRHFUKi/IzZckhTSpHrS/ckuKfEYRkXqHOnUOwE8JiunBOIkrURdPe7kLHcI/yFOn+kLuvS6X9OTzJQLHlTI0axWOga/fCE0hgC1HoHTzP0aCj4i5Pby9zFve0mOGwBTLS/wtAubbC0a+ME54ODIQFp8RyGjUmQEQXhinwRBXbp3wCE3U/cd35IVBsBIeZgSWJwdqKCOmqzaSkA2N8CsT/T4Yw7nLbbr19K2Rfa2jyB1t0n8bLFfxhR6z/DozjAuH96R+jqe8Wj7zf7GuU2WWzyYNZ7SlfbebWhHdskT+eC5/UR+3WxR1/5mvnEjkDw/6iMH1QY9KYf30ZYL8pDZFL7bWpXYE7y4MGAhaH1r8j/1cWyExG9k0VIJ0aYzJ687Vs8uPlUrgZ0jg8pjPhf1WrSIcVHvzQh015yB4Zr6F5vKzU3rmWLBnATGKzmDYLPySzUprGmFkJcBYB5AseaIWqygxR89UQijBXGcEWA7PQagV51P9mJPjXHhs7+0vjS/0BAv8L1puwDq1Jw=\"
      }
    ],
    \"birthdate\": [
      {
        \"source_id\": \"5656d8bfd7036ce6320041a7\",
        \"time\": 1448532616,
        \"signature\": \"G90783tylDD8YywsLQ0qx3P9K3p6GXQS9kMWZ2Vm7A1dM2PK+9vs8HmUeC79bGNhggqefMp7qQRmZYSMfNd95JyVrr7MkEDCnC0zYKdfOXFJLfnqvCJ/RaUSDMnXVrzSG5ZSFAQzaX1lvNTjp4gNMY6KLmQR6u12gFaELEglbZffeywwUaVgjzFbzIAzXR6gzxUOMZoDcmXuhXr4bYbaua7gIU9CKkC+lNots7Yo5TBmjSB2hH6dR3CKChbvjbadBdzXGx3xLhtUl+O/OvNNWlxUhxZrlg8fafEyvnWFB+06SAICGeQjbPusidZvZHDdV/RdOYmNpYLqyfhBdfh9eCF/rN0RvAoJ5oDbjMU6YncIgfvuVs/gRCDXY+GOK7+1Pk1UIhmZh9GEqfaZk2lfeY9l1SjQeUCPxNT72fMa4fvEKzHkB8hIUa66K1qRzPYJGImF42PLxc+EwgTJ69uEUQ4t6qM8TLHb42cTYgtf04/Lie7WMvDj4O/U8NfG0Q3q6762+vLTfahXgaDnSJ8Cu9bPSOJskPpRDtAaFoIsF0UiGyZCNXmw3eVVHE9vleFm79ngpTrmM7BzB/DfL+VKozBPbcWxLhs0yPp4WZN6m4+urXlzEya3+nKWBdbiFM4+67Luae/DdSODpvdqvqn7ywnF27JZ9iiNlv28onUFqfo=\"
      }
    ],
    \"email\": [

    ],
    \"phone\": [

    ],
    \"passports\": [
      {
        \"number\": [
          {
            \"source_id\": \"5656d8bfd7036ce6320041a7\",
            \"time\": 1448532616,
            \"signature\": \"roOA05/av92Y+Y1Y/ekyVszhvqzlAmGMOemBpZYt3OWHwzWlTofLTcCnhBGave3/Bn3n23tJfv/kjTkSwUMzWGRvFYzYTXvBesc0x+68nQ8lmj/i7J5l3cMF3zKVc+wKu2D87C8AQu7FjX954Wm4oMZzWimBD8e7hRy6PWmyOUt6/c/B+QFHVUixFz0NFmvuDntO4mAlCKIO8/fESaZa00mOYTC7LhV2wB7B7qWw0GHoANZiO9B0l8gfoTtZsxB1wWpt3fqWmn12v02Pa00rg6RjnP1AnAaihYXEChJE16W5W0tXj1eRsictWxIZk/eh2gZ/HP4XM6CBG8/Gm2XKcO/lVf8UfeFeZmxEuN9/p+Sc16gJWTwhpxmqWDX/+1XlTOB78WKDZTKnlS2WAGXI5cD95QUj5XIToMk3LVP9DV7lxPY7TACq6Ed2Z7zOBprkloF0ayfBFr9sHRXF6zKzIgircWaXqBETHLbXj9Q4OlK8xho9MC8Rh1zsT86Odz3Kxo151HqzCYbRaWd2U8SSziR2wxE9SjyndNS5BDQbyWHEjV+0en0WwkCQuJ2JE6guJDOeIuIRjUhp55L/8vq54CRkP88nkig2n35VaA8CP2Rcw91HAGKIKmTD5pIp0ip0cQKe4WlqyqRFnAffJ6K8HzvuQE0eCSvM/HeJwHrpBCI=\"
          }
        ],
        \"nationality\": [
          {
            \"source_id\": \"5656d8bfd7036ce6320041a7\",
            \"time\": 1448532616,
            \"signature\": \"pK2VRxOhXsSZ6LxTKB0uh1RRE9Xwm/saSVi80sVJ17XiNCW9gnsxYKbZa5G4sLEYDMXIk8F4ADS9/FT2n6cir3vF2AXITKl7dZ77BahiANRiLMhyIVxR8hf1F2WbqJ5e4VOSBEY3o5kNK2iOUaSNX0u90gUKYQauyOmrk+7TmMxDKbzWHvAqmXn9nOTz76A4qiAzT3tOE49LDvKM6O6jwxEbRJpR07+7KrzQNYeC1SkbM+r+qwBph5j0lpo5BTwW46u+6N9sBHdD77J3PqmyHvxJTUX5Fr36WFDqx838TED5QBY7UBzRNsWhyg7zdq7Hadxtd1wwMnLrzX8rZzJcboniJbRRIJi1F6rW8jn9jOwCpS1alM20qjnXYScM2JbTAHZCCZuIwOL8OKEq2O1P++sEEoSI88QPGrpQSxUyOHgiVdlGekc946UrvO/dYnsX6TJ8WR/V8LbPU/XsbXPicF3QzSjXzU234CgMEsFooPCxdHOOGp8LUbhzmoTHxTHN6hx6oBC7HGq/TKMv2jVhHU3q02UNf57mwsrKLIc7H1A6Acs4PIx9czS7JjsHT5exBuvioHA3rP1c8ga0cQN7TspKzNFTQKUkVoXRCqB7HbleqR+iB+6OV5ucJk2NNTxVT+sLU5qU2xgDHyFg7Iu6RO54MqMNlnjgZdaWgiAg6x4=\"
          }
        ]
      }
    ]
  },
  \"user_data\": {
    \"email\": \"a.a.gorelik@gmail.com\",
    \"phone\": \"+79162194530\"
  }
}";

$identities[] = "
{
  \"_id\": \"5656dc8dd7036c57110041cb\",
  \"first_name\": \"913e07c5edfb5e4b5974c940ba44fd2ab56e85ea926e96dc2b61f554bb342cb2ccc05d71f5e80c7adae9549c3d6c03f0aa1a0d5e5424b63b40268df9dbed73ea\",
  \"last_name\": \"224f5248049cd1db7f43998581b67dde4d1ed9cf2a6407490fdf8c8a9a09bf409bb69b715514dd55d53f8226ef9819e0e30512eeddb2056dd08259c4c666965e\",
  \"birthdate\": \"16dec73c1e6227fa5d1835093caf04ec290bbf91a1d4ffa276e305a95b240d7dd2357ab4c936eeb71143cc764a7cc6dca165d8512d856203ab47b750c816cabc\",
  \"email\": \"249273d4fa52fb6ee4972e9b1c737ebc9d82592ecb96bea6f6ac6b70e0d9710f91c2fea67b97f903267fdd25b08d1996fa0235f5850487eb42f1626f8d276537\",
  \"phone\": \"3287d03c6d7116be9dc68a647b259e56bc758f043517f274bc2713cf38aa761e1b147d684e40c0ee16b9a60760df53c5039da597315f9903d1e3258290262f83\",
  \"passports\": [
    {
      \"number\": \"a2eac49b03855f8a15c9bc1e6dc9c346e39471c8aa4f312c3001c849846b1b16dc805e001740b733391b19521b5d19826dbdfd664a92b3b39ae6c7999396bf91\",
      \"nationality\": \"8e86b6e6736b6171e8f1e3d0d9166b62954707e1b8ac4ca058f4046d2d37c990fae3e62d2daf1f712263094788255c86951cbda70f98e740eb896a886f17c311\"
    }
  ],
  \"created\": 1448533133,
  \"history\": [
    {
      \"time\": 1448533133,
      \"action\": \"created\",
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    },
    {
      \"time\": 1448533133,
      \"action\": \"used\",
      \"cause\": \"buying a ticket\",
      \"fields\": {
        \"first_name\": 1,
        \"last_name\": 1,
        \"birthdate\": 1,
        \"email\": 1,
        \"phone\": 1,
        \"passports\": [
          {
            \"number\": 1,
            \"nationality\": 1
          }
        ]
      },
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    },
    {
      \"time\": 1448533134,
      \"action\": \"check\",
      \"fields\": {
        \"first_name\": 1,
        \"last_name\": 1,
        \"birthdate\": 1,
        \"email\": 1,
        \"phone\": 1,
        \"passports\": [
          {
            \"number\": 1,
            \"nationality\": 1
          }
        ]
      },
      \"diff_fields\": [

      ],
      \"ip\": \"127.0.0.1\",
      \"source\": \"5656d8bfd7036ce6320041a7\"
    }
  ],
  \"verifications\": {
    \"first_name\": [

    ],
    \"last_name\": [

    ],
    \"birthdate\": [

    ],
    \"email\": [

    ],
    \"phone\": [

    ],
    \"passports\": [
      {
        \"number\": [

        ],
        \"nationality\": [

        ]
      }
    ]
  },
  \"user_data\": {
    \"email\": \"aagorelik@gmail.com\",
    \"phone\": \"+79152194530\"
  }
}";


$claims[] = "
{
  \"_id\": \"5656dc8dd7036c57110041cc\",
  \"affected_identities\": [
    {
      \"identity_id\": \"5656da84d7036cdd100041c7\",
      \"type\": 2,
      \"diff\": {
        \"passports\": [
          {
            \"number\": \"a2eac49b03855f8a15c9bc1e6dc9c346e39471c8aa4f312c3001c849846b1b16dc805e001740b733391b19521b5d19826dbdfd664a92b3b39ae6c7999396bf91\"
          }
        ]
      },
      \"refers_to\": [
        \"5656dc8dd7036c57110041cb\"
      ]
    },
    {
      \"identity_id\": \"5656dc8dd7036c57110041cb\",
      \"type\": 1,
      \"refers_to\": [
        \"5656da84d7036cdd100041c7\"
      ]
    }
  ],
  \"created\": 1448533133,
  \"status\" : \"unresolved\",
  \"source_id\": \"5656d8bfd7036ce6320041a7\",
  \"ip\": \"127.0.0.1\"
}";

$claims[] = "
{
  \"_id\": \"5656dcaad7036cdb100041c8\",
  \"affected_identities\": [
    {
      \"identity_id\": \"5656da84d7036cdd100041c7\",
      \"type\": 2,
      \"diff\": {
        \"first_name\": \"b82e6a248b533e179b61e90b6ec747336fb29750c4f4d6b679481d89c2810e734ecfdf40a71b2e453ee89f3a522a8d8a6317566a7e1dd349b668f85ac81b2d52\",
        \"last_name\": \"e69f0aedfeba6515284663e8176d7f5365df1012046596799dce3d0b14541cb15da515a1ac8c43f9d1fd319b95d301031a9adef8ddb0a7c9f2f62f6f256529ae\"
      },
      \"refers_to\": [
        \"5656da84d7036cdd100041c7\"
      ]
    }
  ],
  \"created\": 1448533162,
  \"status\" : \"unresolved\",
  \"source_id\": \"5656d8bfd7036ce6320041a7\",
  \"ip\": \"127.0.0.1\"
}";

$users[] = "
{
  \"created\" : \"1448532612\",
  \"identity_id\" : \"5656da84d7036cdd100041c7\",
  \"email\" : \"a.a.gorelik@gmail.com\",
  \"phone\" : \"+79162194530\",
  \"status\" : \"not_activated\"
}";

$users[] = "
{
  \"created\" : \"1448532612\",
  \"identity_id\" : \"5656dc8dd7036c57110041cb\",
  \"email\" : \"a.a.gorelik2@gmail.com\",
  \"phone\" : \"+79152194530\",
  \"status\" : \"not_activated\"
}";

?>
