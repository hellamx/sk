<?php
/**
 * @var $product;
 */
?>
<p>
    Создан новый товар
</p>
<br>
<table>
    <tr>
        <td style="font-weight: bold">Заголовок</td>
        <td style="font-weight: bold">Цена</td>
        <td style="font-weight: bold">eId</td>
    </tr>
    <tr>
        <td>{{ $product->title }}</td>
        <td>{{ $product->price ?? 'Не указана' }}</td>
        <td>{{ $product->eId }}</td>
    </tr>
</table>
