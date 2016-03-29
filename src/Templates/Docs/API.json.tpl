{
  "swagger": "2.0",
  "info": {
    "title": "API",
    "description": "New super API",
    "version": "1.0.0"
  },
  "host": "example.com",
  "schemes": [
    "http"
  ],
  "basePath": "{basePath}",
  "produces": [
    "application/json"
  ],
  "securityDefinitions": {
    "basicAuth": {
      "type": "basic",
      "description": "HTTP Basic Authentication."
    }
  },
  "paths": {
    "/admin/{route}": {
      "get": {
        "security": [
          {
            "basicAuth": []
          }
        ],
        "summary": "{modelNamePlural} List",
        "description": "Get list of {modelNamePlural}",
        "tags": [
          "{modelNamePlural}"
        ],
        "parameters": [
          {
            "name": "size",
            "in": "request",
            "description": "Amount of {modelNamePlural} on page",
            "required": false
          },
          {
            "name": "page",
            "in": "request",
            "description": "Page for pagination",
            "required": false
          }
        ],
        "responses": {
          "200": {
            "description": "An array of {modelNamePlural}",
            "schema": {
              "type": "object",
              "required": [
                "meta",
                "data"
              ],
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                },
                "data": {
                  "type": "array",
                  "description": "Response data object",
                  "items": {
                    "$ref": "#/definitions/{modelName}List"
                  }
                },
                "paging": {
                  "$ref": "#/definitions/Paging",
                  "description": "Paging object"
                }
              }
            }
          }
        }
      },
      "post": {
        "security": [
          {
            "basicAuth": []
          }
        ],
        "summary": "{modelName} Create",
        "description": "Create a new {modelName}",
        "tags": [
          "{modelNamePlural}"
        ],
        "parameters": [
          {
            "name": "table",
            "in": "body",
            "description": "{modelName} object",
            "required": true,
            "schema": {
              "type": "object",
              "required": [
                {requiredPost}
              ],
              "properties": {
                {propertiesPost}
              }
            }
          }
        ],
        "responses": {
          "200": {
            "description": "Created {modelName}",
            "schema": {
              "type": "array",
              "items": {
                "$ref": "#/definitions/{modelName}"
              }
            }
          }
        }
      }
    },
    "/admin/{route}/{id}": {
      "get": {
        "summary": "{modelName} Read",
        "description": "Get {modelName} by id",
        "tags": [
          "{modelNamePlural}"
        ],
        "responses": {
          "200": {
            "description": "{modelName} object",
            "schema": {
              "type": "object",
              "required": [
                "meta",
                "data"
              ],
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                },
                "data": {
                  "$ref": "#/definitions/{modelName}",
                  "description": "Response data object"
                }
              }
            }
          },
          "422": {
            "description": "Validation error",
            "schema": {
              "type": "object",
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                }
              }
            }
          }
        }
      },
      "put": {
        "summary": "{modelName} Update",
        "description": "Update {modelName} by id",
        "tags": [
          "{modelNamePlural}"
        ],
        "responses": {
          "200": {
            "description": "{modelName} object",
            "schema": {
              "type": "object",
              "required": [
                "meta",
                "data"
              ],
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                },
                "data": {
                  "$ref": "#/definitions/{modelName}",
                  "description": "Response data object"
                }
              }
            }
          }
        }
      },
      "delete": {
        "summary": "{modelName} Delete",
        "description": "Delete {modelName} by id",
        "tags": [
          "{modelNamePlural}"
        ],
        "responses": {
          "200": {
            "schema": {
              "type": "object",
              "required": [
                "meta"
              ],
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                }
              }
            }
          }
        }
      }
    },
    "/admin/{route}/{id}/copy": {
      "post": {
        "summary": "{modelName} Copy",
        "description": "Create new {modelName} from existing {modelName} by id",
        "tags": [
          "{modelNamePlural}"
        ],
        "responses": {
          "200": {
            "schema": {
              "type": "object",
              "required": [
                "meta"
              ],
              "properties": {
                "meta": {
                  "$ref": "#/definitions/Meta",
                  "description": "Meta data"
                },
                "data": {
                  "$ref": "#/definitions/{modelName}",
                  "description": "Response data object"
                }
              }
            }
          }
        }
      }
    }
  },
  "definitions": {
    "Meta": {
      "type": "object",
      "required": [
        "code"
      ],
      "properties": {
        "code": {
          "type": "integer",
          "description": "HTTP response code",
          "example": 200
        }
      }
    },
    "Paging": {
      "type": "object",
      "required": [
        "size",
        "total",
        "current_page",
        "last_page"
      ],
      "properties": {
        "size": {
          "type": "integer",
          "description": "Items per page",
          "example": 20
        },
        "total": {
          "type": "integer",
          "description": "Total items in collection",
          "example": 20
        },
        "current_page": {
          "type": "integer",
          "description": "Current page",
          "example": 20
        },
        "last_page": {
          "type": "integer",
          "description": "Items per page",
          "example": 20
        }
      }
    },
    "{modelName}List": {
      "type": "object",
      "description": "",
      "required": [
        {requiredList}
      ],
      "properties": {
        {propertiesList}
      }
    },
    "{modelName}": {
      "type": "object",
      "description": "",
      "required": [
        {required}
      ],
      "properties": {
        {properties}
      }
    }
  }
}