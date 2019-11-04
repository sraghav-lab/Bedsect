<?php
include_once("config.php");
$organism = $_POST['organism'];
$search = $_POST['search'];
$cell = $_POST['cell'];

#echo $organism;

    if(empty($cell)){
        if(!empty($organism) && empty($search)){
            $query = "SELECT DISTINCT TF FROM gtrd_data WHERE Organism = '$organism'";
            $fetch_tf = mysqli_query($conn, $query);
                if(!$fetch_tf){
                        die('Unable to fetch information (Error: 10)' . mysqli_error($connect));
		};
	    echo '<option value="NA"></option>';
            while( $row = mysqli_fetch_array($fetch_tf) ){
                    echo '<option value="'.$row['TF'].'">'.$row['TF'].'</option>';
            };
        } elseif(!empty($organism) && !empty($search)){
            $query = "SELECT DISTINCT Cellline FROM gtrd_data WHERE Organism = '$organism' AND TF ='$search'";
            $fetch_cell = mysqli_query($conn, $query);
                if(!$fetch_cell){
                        die('Unable to fetch information (Error: 10)' . mysqli_error($connect));
                };
            echo '<option value="NA"></option>';
            while( $row = mysqli_fetch_array($fetch_cell) ){
                    echo '<option value="'.$row['Cellline'].'">'.$row['Cellline'].'</option>';
            };
        }
    } else { 
        $query = "SELECT * FROM gtrd_data WHERE TF = '$search' AND Organism = '$organism' AND Cellline = '$cell'";
        $search_query = mysqli_query($conn, $query);

        if(!$search_query){
        // die('QUERY FAILED' . mysqli_error($connect));
        }
        echo "<br><label for='gtrd_search'>GTRD search results :</label><br>
        <table class='table table-striped gtrd_search' id='gtrd_search'>
            <thead>
                <th>Experiment</th>
                <th>Antibody</th>
                <th>Factor</th>
                <th>Origin/Cell line</th>
                <th>Treatment</th>
                <th>Reference</th>
                <th>Add</th>
            </thead>
            <tbody>";
        while( $row = mysqli_fetch_array($search_query) ){
        echo   "<tr>
                <td>".$row['Experiment']."</td>
                <td>".$row['Antibody']."</td>
                <td>".$row['TF']."</td>
                <td>".$row['Cellline']."</td>
                <td>".$row['Treatment']."</td>
                <td>".$row['Ref_id']."</td>
                <td><button class='btn btn-danger btn-add'>Add</button></td>
                </tr>";
            }
        echo"</tbody>
            </table>";
    }
?>
