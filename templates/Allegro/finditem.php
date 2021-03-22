
<?= $this->Form->create($allegro,['type'=>'get']);?>

<h4>Find Item for: <?php echo $itemss?>
<?= $this->Form->control('itemss',['type'=>'text',$itemss]);?></h4>

<?= $this->Form->button('Find item',array('action'=>'finditem',$itemss));?>

<?= $this->Form->end();?>

<?php if(isset($allegroItems)){?>

<table>
    <tr>
        <th>itemy:</th>
        <th></th>
    </tr>
    <?php
        foreach($allegroItems as $key){
          echo '<pre>';
          print_r($key);
          echo '</pre>';
        }
    ?>

</table>
<?php }else{?><h3>There is no items</h3><?php }?>
