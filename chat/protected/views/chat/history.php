<h1><?php echo $this->pageTitle ?></h1>

<table class="table table-striped">
    <tbody>
    <?php for ($i = count($msgs)-1; $i>=0; $i--):?>
        <tr><td><?php echo $msgs[$i]['name'] ?></td><td><?php echo $msgs[$i]['message'] ?></td></tr>
    <?php endfor ?>
    </tbody>
</table>