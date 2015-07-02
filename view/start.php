<div class="col-sm-12">
    <h1>Welcome to the Arcwind DataStore</h1>


    <br/>
    <hr/>
</div>


<div class="col-sm-12">
    <div class="col-sm-5">
        <div class="well bs-component">
            <h2>Kontaktieren Sie uns!</h2>
            <br/>

            <form method="post" action="<?php echo $bp; ?>contact/contactReceive" class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                        <input name="mail" type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputContent" class="col-sm-2 control-label">Text</label>

                    <div class="col-sm-10 col-sm-offset-2">
                    <textarea name="content" id="inputContent" class="form-control" cols="40" rows="10"
                              placeholder="Wir freuen uns auf Ihr Feedback!"></textarea>
                    </div>
                </div>

                <input type="hidden" name="form" value="startPage">

                <div class="">
                    <button class="btn btn-primary pull-right" type="submit">
                        Go!
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <div class="col-sm-7">
        <img src="./wizzzard.svg" style="float:right;"/>
        <h4>Contact Us</h4>


        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat.
        </p>

        <h4>More Information</h4>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua.
        </p>
    </div>

</div>

<div class="col-sm-12">
    &nbsp;
    <br/>
    <hr/>
</div>
