<style type="text/css">
#marketing table {
    width: 100%;
    margin: 0 0 30px 0;
}

#marketing table td, #marketing table th {
    padding: 5px;
    border-bottom: 1px solid gray;
}

#marketing table td {
    border-right: 1px solid gray;
}

#marketing table tr > td {
    border-left: 1px solid gray;
}

#marketing #promo {
    width: 25%;
    float: left;
}

#marketing legend {
    color: gray;
    margin-bottom: 20px;
    display: block;
    width: 100%;
}

#marketing label {
    color: #555;
}

#marketing input {
    display: block;
    width: 100%
}

#marketing input[type="submit"] {
    width: auto;
}

#marketing #promo_list {
    width: 73%;
    float: right;
}

</style>
<div id="marketing" class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <?php include $this->_page.'.php'; ?>
</div>