<div class="navbar navbar-default navbar-material-wizzzard">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo $bp; ?>">Arcwind</a>
  </div>
  <div class="navbar-collapse collapse navbar-responsive-collapse">
    <ul class="nav navbar-nav">
      <?php foreach ($navigation as $link) {
    if ($link['type'] == 'menu') {
        ?>
        <li class="dropdown <?php if ($link['link'] == $navigationActive) {
    echo 'active';
}
        ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php echo $link['title'];
        ?>
              <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php
            foreach ($link['menu'] as $innerLink) {
                if ($innerLink['link'] == 'divider') {
                    ?>
                <li class="divider"></li>
                <li class="dropdown-header"><?php echo $innerLink['title'];
                    ?></li>
                <?php

                } else {
                    ?>
                  <li>
                    <a href="<?php echo $bp.$innerLink['link'];
                    ?>">
                      <?php echo $innerLink['title'];
                    ?>
                    </a>
                  </li>
                <?php

                }
            }
        ?>
          </ul>
        </li>

        <?php

    } else {
        ?>
          <li class="<?php if ($link['link'] == $navigationActive) {
    echo 'active';
}
        ?>">
            <a href="<?php echo $bp.$link['link'];
        ?>">
              <?php echo $link['title'];
        ?>
            </a>
          </li>
          <?php

    }
} ?>


    </ul>
    <ul class="nav navbar-nav navbar-right">
      <?php foreach ($navigationRight as $link) {
    if ($link['type'] == 'menu') {
        ?>
        <li class="dropdown  <?php if ($link['link'] == $navigationActive) {
    echo 'active';
}
        ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php echo $link['title'];
        ?>
              <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php
            foreach ($link['menu'] as $innerLink) {
                if ($innerLink['link'] == 'divider') {
                    ?>
                <li class="divider"></li>
                <li class="dropdown-header"><?php echo $innerLink['title'];
                    ?></li>
                <?php

                } else {
                    ?>
                  <li>
                    <a href="<?php echo $bp.$innerLink['link'];
                    ?>">
                      <?php echo $innerLink['title'];
                    ?>
                    </a>
                  </li>
                <?php

                }
            }
        ?>
          </ul>
        </li>

        <?php

    } else {
        ?>
        <li class="<?php if ($link['link'] == $navigationActive) {
    echo 'active';
}
        ?>">
            <a href="<?php echo $bp.$link['link'];
        ?>">
              <?php echo $link['title'];
        ?>
            </a>
          </li>
          <?php

    }
} ?>

    </ul>


  </div>
</div>
