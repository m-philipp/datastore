<div class="col-sm-12">
    <div class="well bs-component">
        <h1>API Data Access Documentation:</h1>


        <hr>

        <h1>Save Data through the API</h1>


        Data which is stored in the datastore usually consists of a timestamp value pair. The Timestamps are
        milliseconds since the unic epoc. Therefore the Timestamps are checked to be bigger than 1400000000000.
        <br/>
        <br/>

        To ensure that the Data is not corrupted there is a very simple Checksum check before the data can be stored in
        the store. The Checksum is calculated via the formula <var>checksum = timestamp + value</var>.
        <br/>
        <br/>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Speichern eines einzelnen neuen Wertes</h3>
            </div>


            <div class="panel-body">
                The API Store is accessible via <code>POST</code>.
                <h4>URL:</h4>
<pre>
/api/store/<em class="text-muted">StreamID</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>{
    "type": "object",
    "properties": {
        "time": {"type": "string"},
        "value": {"type": "string"},
        "checksum": {"type": "string"}
    },
    "additionalProperties": false
}</pre>
                <h4>Reply-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Speichern eines Arrays neuer Wertes</h3>
            </div>
            <div class="panel-body">
                The API Store is accessible via <code>POST</code>.
                <h4>URL:</h4>
<pre>
/api/store-list/<em class="text-muted">StreamID</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>{
  "type": "array",
  "items": {
    "type": "object",
    "properties": {
      "time": {"type": "string"},
      "value": {"type": "string"},
      "checksum": {"type": "string"}
    },
    "additionalProperties": false
  },
  "minItems": 1
}</pre>
                <h4>Reply-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Speichern eines einzelnen neuen Wertes</h3>
            </div>


            <div class="panel-body">
                The API Store is accessible via <code>GET</code>.
                <br/>
                <br/>
                This is a simplified Version to store data. You can not save your own timestamp. Instead the server will
                set a timestamp to the point where the server received the request.
                <h4>URL:</h4>
<pre>
/api/store/val/<em class="text-muted">StreamID</em>/<em class="text-muted">Value</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
                <h4>Reply-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
            </div>
        </div>

        <hr>

        <h1>Retrieve Stored Data from the API</h1>

        The Replies contain JSON Objects with the <code>data</code> property. This property contains an array of
        the replied datapoints. Each Datapoint consists of a timestamp and a value.
        <br/>
        <br/>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Retrieve Data of a certain timespan</h3>
            </div>
            <div class="panel-body">
                The API Store is accessible via <code>GET</code>.
                <br>
                The Timestamps via FROM and TO are milliseconds since the unix epoch.<br/>

                <h4>URL:</h4>
<pre>
/api/retrieve/<em class="text-muted">StreamID</em>/<em class="text-muted">FROM</em>/<em class="text-muted">TO</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
                <h4>Reply-Scheme:</h4>
<pre>{
    "type": "object",
    "properties": {
        "data" : {
            "type": "array",
            "items": {
                "type": "array",
                "items": {
                    "type": "number
                }
            }
        }
    }
}</pre>
                <h4>Example Reply:</h4>
<pre>{"data":
    [
        [1436658670236.3,0],
        [1436658670394.2,0.062790519529313],
        [1436658670529.2,0.1253332335643]
    ]
}</pre>
            </div>
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Retrieve Data of a since a certain time</h3>
            </div>
            <div class="panel-body">
                The API Store is accessible via <code>GET</code>.
                <br>
                The Timestamps via FROM are milliseconds since the unix epoch.<br/>

                <h4>URL:</h4>
<pre>
/api/retrieve/<em class="text-muted">StreamID</em>/<em class="text-muted">FROM</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
                <h4>Reply-Scheme:</h4>
<pre>{
    "type": "object",
    "properties": {
        "data" : {
            "type": "array",
            "items": {
                "type": "array",
                "items": {
                    "type": "number
                }
            }
        }
    }
}</pre>
                <h4>Example Reply:</h4>
<pre>{"data":
    [
        [1436658670236.3,0],
        [1436658670394.2,0.062790519529313],
        [1436658670529.2,0.1253332335643]
    ]
}</pre>
            </div>
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Retrieve the last Data</h3>
            </div>
            <div class="panel-body">
                The API Store is accessible via <code>GET</code>.
                <br>
                The Timestamps via FROM are milliseconds since the unix epoch.<br/>

                <h4>URL:</h4>
<pre>
/api/retrieve/<em class="text-muted">StreamID</em>/numValues/<em class="text-muted">AMOUNT</em>
</pre>
                <h4>Access-Control:</h4>
<pre>
private
</pre>
                <h4>Request-Scheme:</h4>
<pre>
<em class="text-muted">empty</em>
</pre>
                <h4>Reply-Scheme:</h4>
<pre>{
    "type": "object",
    "properties": {
        "data" : {
            "type": "array",
            "items": {
                "type": "array",
                "items": {
                    "type": "number
                }
            }
        }
    }
}</pre>
                <h4>Example Reply:</h4>
<pre>{"data":
    [
        [1436658670236.3,0],
        [1436658670394.2,0.062790519529313],
        [1436658670529.2,0.1253332335643]
    ]
}</pre>
            </div>
        </div>


    </div>

</div>