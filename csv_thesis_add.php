 
 <?php
    require 'vendor/autoload.php';
    require_once 'dbconnect.php';

    date_default_timezone_set("Asia/Bangkok");

    if (isset($_POST['submitBtn'])) {
        if (isset($_FILES['fileDirectory'])) {
            $file_ary = reArrayFiles($_FILES['fileDirectory']);
            // var_dump($file_ary);
            // echo $file_ary[0]["name"];
            // foreach($file_ary as $file) {
            //     echo "$file[name] <br>";
            // }
        }
        if (!empty($_FILES['csvFile']['name'])) {
            $fileCSV = fopen($_FILES['csvFile']['tmp_name'], "r");
            $count = 1;
            while (($data = fgetcsv($fileCSV, 10000, ",")) !== false) {
                if ($count > 1) {
                    $printed_year       = $data[0];  //ปีที่พิมพ์เล่ม
                    $semester           = $data[1];  //โปรเจคสำเร็จ(เทอม)
                    $approval_year      = $data[2];  //โปรเจคสำเร็จ(ปีการศึกษา)
                    $thai_name          = $data[3];  //ชื่อโครงงานภาษาไทย
                    $english_name       = $data[4];  //ชื่อโครงงานภาษาอังกฤษ
                    $member1_student_id = $data[5];  //รหัสนศ. คนที่ 1
                    $member1_name       = $data[6];  //ชื่อ คนที่ 1
                    $member1_lastname   = $data[7];  //นามสกุล คนที่ 1
                    $member2_student_id = $data[8];  //รหัสนศ. คนที่ 2
                    $member2_name       = $data[9];  //ชื่อ คนที่ 2
                    $member2_lastname   = $data[10]; //นามสกุล คนที่ 2
                    $member3_student_id = $data[11]; //รหัสนศ. คนที่ 3
                    $member3_name       = $data[12]; //ชื่อ คนที่ 3
                    $member3_lastname   = $data[13]; //นามสกุล คนที่ 3
                    $name_advisor       = $data[14]; //ชื่ออาจารย์ที่ปรึกษาหลัก
                    $name_coAdvisor     = $data[15]; //ชื่ออาจารย์ที่ปรึกษาร่วม 1
                    $name_chairman      = $data[16]; //ชื่อประธานสอบ
                    $name_director1     = $data[17]; //ชื่อกรรมการ คนที่ 1
                    $name_director2     = $data[18]; //ชื่อกรรมการ คนที่ 2
                    $keyword1           = $data[19]; //คำสำคัญ 1
                    $keyword2           = $data[20]; //คำสำคัญ 2
                    $keyword3           = $data[21]; //คำสำคัญ 3
                    $abstract           = $data[22]; //บทคัดย่อ
                    $approval_file      = $data[23]; //ไฟล์หนำอนุมัติ
                    $thesis_file        = $data[24]; //ไฟล์เล่ม
                    $poster_file        = $data[25]; //ไฟล์โปสเตอร์
                    // echo "<span style='color:red;'>" . $thai_name . "</span><br>";
                    // echo $keyword1 . " " . $keyword2 . " " . $keyword3 . "<br>" . "-----------------------------------" . "<br>";

                    //ตำสำคัญ
                    $keywords = '';
                    for ($i = 1; $i <= 3; $i++) {
                        if (${"keyword$i"} !== '') {
                            $keywords .= ${"keyword$i"};
                            if ($i != 3) {
                                $keywords .= ", ";
                            }
                        }
                    }

                    //สมาชิก
                    for ($i = 1; $i <= 3; $i++) {
                        if (${"member" . $i . "_name"} !== '') {
                            if (strpos(${"member" . $i . "_name"}, "นาย") !== false) {
                                ${"member" . $i . "_name"} = explode("นาย", ${"member" . $i . "_name"});
                                ${"member" . $i . "_prefix"} =  "นาย";
                            } else if (strpos(${"member" . $i . "_name"}, "นางสาว") !== false) {
                                ${"member" . $i . "_name"} = explode("นางสาว", ${"member" . $i . "_name"});
                                ${"member" . $i . "_prefix"} =  "นางสาว";
                            } else if (strpos(${"member" . $i . "_name"}, "นาง") !== false) {
                                ${"member" . $i . "_name"} = explode("นาง", ${"member" . $i . "_name"});
                                ${"member" . $i . "_prefix"} =  "นาง";
                            }
                        }
                    }

                    //อาจารย์ที่ปรึกษาหลัก
                    if (strpos($name_advisor, "อาจารย์") !== false) {
                        $name_advisor = explode("อาจารย์", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "อาจารย์";
                    } else if (strpos($name_advisor, "รองศาสตราจารย์ ดร.") !== false) {
                        $name_advisor = explode("รองศาสตราจารย์ ดร.", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "รองศาสตราจารย์ ดร.";
                    } else if (strpos($name_advisor, "รองศาสตราจารย์") !== false) {
                        $name_advisor = explode("รองศาสตราจารย์", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "รองศาสตราจารย์";
                    } else if (strpos($name_advisor, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                        $name_advisor = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "ผู้ช่วยศาสตราจารย์ ดร.";
                    } else if (strpos($name_advisor, "ผู้ช่วยศาสตราจารย์") !== false) {
                        $name_advisor = explode("ผู้ช่วยศาสตราจารย์", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "ผู้ช่วยศาสตราจารย์";
                    } else if (strpos($name_advisor, "ศาสตราจารย์ ดร.") !== false) {
                        $name_advisor = explode("ศาสตราจารย์ ดร.", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "ศาสตราจารย์ ดร.";
                    } else if (strpos($name_advisor, "ศาสตราจารย์") !== false) {
                        $name_advisor = explode("ศาสตราจารย์", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "ศาสตราจารย์";
                    } else if (strpos($name_advisor, "ดร.") !== false) {
                        $name_advisor = explode("ดร.", $name_advisor);
                        $name_advisor = explode(" ", $name_advisor[1]);
                        $surname_advisor = $name_advisor[1];
                        $prefix_advisor = "ดร.";
                    }

                    //อาจารย์ที่ปรึกษาร่วม
                    if ($name_coAdvisor !== '') {
                        if (strpos($name_coAdvisor, "อาจารย์") !== false) {
                            $name_coAdvisor = explode("อาจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "อาจารย์";
                        } else if (strpos($name_coAdvisor, "รองศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("รองศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "รองศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "รองศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("รองศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "รองศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "ผู้ช่วยศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "ผู้ช่วยศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("ผู้ช่วยศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "ผู้ช่วยศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("ศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "ศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "ศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("ศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "ศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ดร.") !== false) {
                            $name_coAdvisor = explode("ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $prefix_coAdvisor = "ดร.";
                        }
                    }

                    //ประธานกรรมการ
                    if (strpos($name_chairman, "อาจารย์") !== false) {
                        $name_chairman = explode("อาจารย์", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "อาจารย์";
                    } else if (strpos($name_chairman, "รองศาสตราจารย์ ดร.") !== false) {
                        $name_chairman = explode("รองศาสตราจารย์ ดร.", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "รองศาสตราจารย์ ดร.";
                    } else if (strpos($name_chairman, "รองศาสตราจารย์") !== false) {
                        $name_chairman = explode("รองศาสตราจารย์", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "รองศาสตราจารย์";
                    } else if (strpos($name_chairman, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                        $name_chairman = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "ผู้ช่วยศาสตราจารย์ ดร.";
                    } else if (strpos($name_chairman, "ผู้ช่วยศาสตราจารย์") !== false) {
                        $name_chairman = explode("ผู้ช่วยศาสตราจารย์", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "ผู้ช่วยศาสตราจารย์";
                    } else if (strpos($name_chairman, "ศาสตราจารย์ ดร.") !== false) {
                        $name_chairman = explode("ศาสตราจารย์ ดร.", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "ศาสตราจารย์ ดร.";
                    } else if (strpos($name_chairman, "ศาสตราจารย์") !== false) {
                        $name_chairman = explode("ศาสตราจารย์", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "ศาสตราจารย์";
                    } else if (strpos($name_chairman, "ดร.") !== false) {
                        $name_chairman = explode("ดร.", $name_chairman);
                        $name_chairman = explode(" ", $name_chairman[1]);
                        $surname_chairman = $name_chairman[1];
                        $prefix_chairman = "ดร.";
                    }

                    //กรรมการคนที่ 1
                    if (strpos($name_director1, "อาจารย์") !== false) {
                        $name_director1 = explode("อาจารย์", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "อาจารย์";
                    } else if (strpos($name_director1, "รองศาสตราจารย์ ดร.") !== false) {
                        $name_director1 = explode("รองศาสตราจารย์ ดร.", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "รองศาสตราจารย์ ดร.";
                    } else if (strpos($name_director1, "รองศาสตราจารย์") !== false) {
                        $name_director1 = explode("รองศาสตราจารย์", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "รองศาสตราจารย์";
                    } else if (strpos($name_director1, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                        $name_director1 = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "ผู้ช่วยศาสตราจารย์ ดร.";
                    } else if (strpos($name_director1, "ผู้ช่วยศาสตราจารย์") !== false) {
                        $name_director1 = explode("ผู้ช่วยศาสตราจารย์", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "ผู้ช่วยศาสตราจารย์";
                    } else if (strpos($name_director1, "ศาสตราจารย์ ดร.") !== false) {
                        $name_director1 = explode("ศาสตราจารย์ ดร.", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "ศาสตราจารย์ ดร.";
                    } else if (strpos($name_director1, "ศาสตราจารย์") !== false) {
                        $name_director1 = explode("ศาสตราจารย์", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "ศาสตราจารย์";
                    } else if (strpos($name_director1, "ดร.") !== false) {
                        $name_director1 = explode("ดร.", $name_director1);
                        $name_director1 = explode(" ", $name_director1[1]);
                        $surname_director1 = $name_director1[1];
                        $prefix_director1 = "ดร.";
                    }

                    //กรรมการคนที่ 2
                    if (strpos($name_director2, "อาจารย์") !== false) {
                        $name_director2 = explode("อาจารย์", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "อาจารย์";
                    } else if (strpos($name_director2, "รองศาสตราจารย์ ดร.") !== false) {
                        $name_director2 = explode("รองศาสตราจารย์ ดร.", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "รองศาสตราจารย์ ดร.";
                    } else if (strpos($name_director2, "รองศาสตราจารย์") !== false) {
                        $name_director2 = explode("รองศาสตราจารย์", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "รองศาสตราจารย์";
                    } else if (strpos($name_director2, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                        $name_director2 = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "ผู้ช่วยศาสตราจารย์ ดร.";
                    } else if (strpos($name_director2, "ผู้ช่วยศาสตราจารย์") !== false) {
                        $name_director2 = explode("ผู้ช่วยศาสตราจารย์", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "ผู้ช่วยศาสตราจารย์";
                    } else if (strpos($name_director2, "ศาสตราจารย์ ดร.") !== false) {
                        $name_director2 = explode("ศาสตราจารย์ ดร.", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "ศาสตราจารย์ ดร.";
                    } else if (strpos($name_director2, "ศาสตราจารย์") !== false) {
                        $name_director2 = explode("ศาสตราจารย์", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "ศาสตราจารย์";
                    } else if (strpos($name_director2, "ดร.") !== false) {
                        $name_director2 = explode("ดร.", $name_director2);
                        $name_director2 = explode(" ", $name_director2[1]);
                        $surname_director2 = $name_director2[1];
                        $prefix_director2 = "ดร.";
                    }

                    // thesis
                    if ($thesis_file !== '') {
                        $thesis_file = $thesis_file . ".pdf";
                        foreach($file_ary as $file) {
                            // echo $file['name'];
                            if($file['name'] == $thesis_file) {
                                $thesis_temp = $file['tmp_name'];
                                $thesis_name = $file['name'];
                                $thesis_upload_path = 'FileStorage/thesis/' . $thesis_name;
                                move_uploaded_file($thesis_temp, $thesis_upload_path);
                            } else {
                                $thesis_upload_path = '';
                            }
                        }
                    } else {
                        $thesis_file = "NULL";
                    }

                    // poster
                    if ($poster_file !== '') {
                        $poster_file = $poster_file . ".pdf";
                        foreach($file_ary as $file) {
                            // echo $file['name'];
                            if($file['name'] == $poster_file) {
                                $poster_temp = $file['tmp_name'];
                                $poster_name = $file['name'];
                                $poster_upload_path = 'FileStorage/poster/' . $poster_name;
                                move_uploaded_file($poster_temp, $poster_upload_path);
                                // echo "$poster_upload_path <br>";
                            } else {
                                $poster_upload_path = '';
                            }
                        }
                    } else {
                        $thesis_file = "NULL";
                    }

                    // approval file
                    if ($approval_file !== '') {
                        $approval_file = $approval_file . ".pdf";
                        foreach($file_ary as $file) {
                            // echo $file['name'];
                            if($file['name'] == $approval_file) {
                                $approval_temp = $file['tmp_name'];
                                $approval_name = $file['name'];
                                $approval_upload_path = 'FileStorage/approval/' . $approval_name;
                                move_uploaded_file($approval_temp, $approval_upload_path);
                                echo "poster : $approval_upload_path <br>";
                            } else {
                                $approval_upload_path = '';
                            }
                        }
                    } else {
                        $poster_file = "NULL";
                    }


                    // $sql = "INSERT INTO thesis_document()";
                    // $stmt = $conn->prepare($sql);
                }
                $count++;
            }
            fclose($fileCSV);
        } else {
            // header("Location: thesisadd");
            echo "no";
        }
    } else {
        die("มีบางอย่างผิดพลาด");
    }

    function reArrayFiles(&$file_post)
    {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }
    ?>
