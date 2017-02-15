{
  "basePath": "/api/",
  "host": "<?php echo $_SERVER['SERVER_NAME']; ?>",
  "info": {
    "description": "The api for the CoWaBoo project",
    "title": "CoWaboo API",
    "version": "1.0.0"
  },
  "paths": {
    "/entry": {
      "post": {
        "description": "Create a new entry",
        "parameters": [
          {
            "description": "Author secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory to which the entry will be created",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          },
          {
            "description": "tags of the entry",
            "in": "formData",
            "name": "tags",
            "required": true,
            "type": "string"
          },
          {
            "description": "value of the entry",
            "in": "formData",
            "name": "value",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Create a new entry",
        "tags": [
          "Entries"
        ]
      },
      "put": {
        "description": "Modify an entry",
        "parameters": [
          {
            "description": "Author secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory to which the entry will be modified",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          },
          {
            "description": "hash of the entry to be modified",
            "in": "formData",
            "name": "hash",
            "required": true,
            "type": "string"
          },
          {
            "description": "new value of the entry",
            "in": "formData",
            "name": "newValue",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Modify an entry",
        "tags": [
          "Entries"
        ]
      },
      "delete": {
        "description": "Delete an entry",
        "parameters": [
          {
            "description": "Author secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory to which the entry will be modified",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          },
          {
            "description": "hash of the entry to be modified",
            "in": "formData",
            "name": "hash",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Delete an entry",
        "tags": [
          "Entries"
        ]
      }
    },
    "/entry/conf": {
      "post": {
        "description": "add a configuration to an entry",
        "parameters": [
          {
            "description": "Author secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory to which the entry will be modified",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          },
          {
            "description": "hash of the entry to be modified",
            "in": "formData",
            "name": "hash",
            "required": true,
            "type": "string"
          },
          {
            "description": "name of the configuration",
            "in": "query",
            "name": "metadata name",
            "required": true,
            "type": "string"
          },
          {
            "description": "value of the configuration",
            "in": "query",
            "name": "metadata value",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Add a configuration to an entry",
        "tags": [
          "Entries"
        ]
      }
    },
    "/observatory": {
      "post": {
        "description": "Create a new observatory",
        "parameters": [
          {
            "description": "Author secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the new observatory",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          }
        },
        "summary": "Create a new observatory",
        "tags": [
          "Observatories"
        ]
      },
      "get": {
        "description": "Get an observatory info",
        "parameters": [
          {
            "description": "ID of the observatory you want the info",
            "in": "query",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Get an observatory info",
        "tags": [
          "Observatories"
        ]
      },
      "delete": {
        "description": "Delete an observatory",
        "parameters": [
          {
            "description": "User secret key",
            "in": "formData",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "ID of the observatory you want to delete",
            "in": "query",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Delete an observatory",
        "tags": [
          "Observatories"
        ]
      }
    },
    "/observatory/conf": {
      "post": {
        "description": "Add a configuration value to an observatory",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "ID of the observatory you want to add a configuration value to",
            "in": "query",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          },
          {
            "description": "name of the configuration",
            "in": "query",
            "name": "metadata name",
            "required": true,
            "type": "string"
          },
          {
            "description": "value of the configuration",
            "in": "query",
            "name": "metadata value",
            "required": false,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Get an observatory info",
        "tags": [
          "Observatories"
        ]
      }
    },
    "/taglist/": {
      "get": {
        "description": "Get all tags by observatories",
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          }
        },
        "summary": "Get all tags by observatories",
        "tags": [
          "Tags"
        ]
      }
    },
    "/user/observatories": {
      "delete": {
        "description": "Unsubscribe user from an observatory",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Unsubscribe user from an observatory",
        "tags": [
          "Observatories",
          "Users"
        ]
      },
{{--       "get": {
        "description": "Get user observatories",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          }
        },
        "summary": "Get user observatories",
        "tags": [
          "Observatories",
          "Users"
        ]
      }, --}}
      "post": {
        "description": "Subscribe user to an observatory",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          },
          {
            "description": "Id of the observatory",
            "in": "formData",
            "name": "observatoryId",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          },
          "404": {
            "description": "unknown observatory"
          }
        },
        "summary": "Subscribe user to an observatory",
        "tags": [
          "Observatories",
          "Users"
        ]
      }
    },
{{--     "/user/observatories/unsubscribed": {
      "get": {
        "description": "Get user unsubscribed observatories",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          }
        },
        "summary": "Get user unsubscribed observatories",
        "tags": [
          "Observatories",
          "Users"
        ]
      }
    }, --}}
    "/user": {
      "get": {
        "description": "Get user info",
        "parameters": [
          {
            "description": "User secret key",
            "in": "query",
            "name": "secretKey",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "401": {
            "description": "unknown user"
          }
        },
        "summary": "Get user info",
        "tags": [
          "Users"
        ]
      },
      "post": {
        "description": "Create a new user",
        "parameters": [
          {
            "description": "new user email",
            "in": "query",
            "name": "email",
            "required": true,
            "type": "string"
          }
        ],
        "produces": [
          "application/json"
        ],
        "responses": {
          "200": {
            "description": "successful operation"
          },
          "403": {
            "description": "no email given"
          },
          "409": {
            "description": "user already exists"
          },
          "500": {
            "description": "problem while creating the user"
          }
        },
        "summary": "Create a new user",
        "tags": [
          "Users"
        ]
      }
    }
  },
  "produces": [
    "application/json"
  ],
  "swagger": "2.0",
  "tags": [
    {
      "description": "Everything about tags",
      "name": "Tags"
    },
    {
      "description": "Everything about observatories",
      "name": "Observatories"
    },
    {
      "description": "Everything about entries",
      "name": "Entries"
    },
    {
      "description": "Everything about users",
      "name": "Users"
    }
  ]
}
