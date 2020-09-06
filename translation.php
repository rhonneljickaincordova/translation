<?php
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    require 'vendor/autoload.php';

    $wikiNumber = $_POST['wikiNumber'];

    $target_dir = "uploads";

    
    $tagalog_target_file = $target_dir . basename($_FILES["tagalogFile"]["name"]);
  
    $cebuano_target_file = $target_dir . basename($_FILES["cebuanoFile"]["name"]);
  

    if(!file_exists($cebuano_target_file) && !file_exists($tagalog_target_file)){
        // READ  CEBUANO by period.

        $tmp_name = $_FILES["cebuanoFile"]["tmp_name"];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["cebuanoFile"]["name"]);
        move_uploaded_file($tmp_name, "$target_dir/$name");


        $myfile = fopen("$target_dir/$name", "r") or die("Unable to open file!");
        $file = fread($myfile,filesize("$target_dir/$name"));
        $cebuano = split("\.",$file); 
        fclose($myfile);

        
        $tmp_name = $_FILES["tagalogFile"]["tmp_name"];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["tagalogFile"]["name"]);
        move_uploaded_file($tmp_name, "$target_dir/$name");



        // READ  TAGALOG by period.
        $myfile = fopen("$target_dir/$name", "r") or die("Unable to open file!");
        $file = fread($myfile,filesize("$target_dir/$name"));
        $tagalog = split("\.",$file); 
            
        fclose($myfile);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'FILIPINO');
            $sheet->setCellValue('C1', 'CEBUANO');

            
            $columnACounter = $wikiNumber;
            $rowindexCounter = 2;
            foreach($tagalog as $key => $val){
                if($tagalog != null){
                    $id = "Wiki-".$columnACounter;
                    
                    $sheet->setCellValue('A'.$rowindexCounter, $id);
                    $sheet->setCellValue('B'.$rowindexCounter, $val);
                    $sheet->setCellValue('C'.$rowindexCounter, $cebuano[$key]);

                    $rowindexCounter++;
                    $columnACounter++;
                }
                
            }

            $writer = new Xlsx($spreadsheet);
            $file_path_name = "result/tagalog-cebuano-".$wikiNumber."-".($columnACounter-1).".xlsx";
            $writer->save($file_path_name);

            
            header("Location: ". $file_path_name);

    }else{
        echo "No files uploaded";
    }

?>