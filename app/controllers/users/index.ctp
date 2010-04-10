<h2>Users</h2>
<table>
    <tr>
        <th>Id</th>
    </tr>
    <?php foreach ($users as $user) { ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
    </tr>
    <?php } ?>
</table>