 
 <?php
    session_start();
    $import_by = $_SESSION['prefix'] . $_SESSION['name'] . " " . $_SESSION['lastname'];
    $_SESSION['countInsertSuccess'] = 0;
    require 'vendor/autoload.php';
    require_once 'dbconnect.php';

    date_default_timezone_set("Asia/Bangkok");

    if (isset($_POST['submitBtn'])) {
        //1 = ผู้ดูแลระบบ 2 = เจ้าหน้าที่ 3 = เจ้าหน้าที่ชั่วคราว 4 = อาจารย์ 5 = นักศึกษา 
        $thesis_status = 1;
        $approval_status = 1;
        if (isset($_SESSION['role'])) {
            $thesis_status = 1; // 0 = ไม่เผยแพร่, 1 = เผยแพร่, 2 = Archove
            if ($_SESSION['role'] == 3) { //ถ้าเป็นเจ้าหน้าที่
                $approval_status = 0; // 0 = รออนุมัติ, 1 = อนุมัติ
            } else {
                $approval_status = 1; // 0 = รออนุมัติ, 1 = อนุมัติ
            }
        }


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
                    // $member1_student_id = '';
                    // $member1_prefix = '';
                    // $member1_name = '';
                    // $member1_lastname = '';
                    // $member2_student_id = '';
                    // $member2_prefix = '';
                    // $member2_name = '';
                    // $member2_lastname = '';
                    // $member3_student_id = '';
                    $member3_prefix = '';
                    // $member3_name = '';
                    // $member3_lastname = '';

                    for ($i = 1; $i <= 3; $i++) {
                        if (${"member" . $i . "_name"} !== '') {
                            if (strpos(${"member" . $i . "_name"}, "นาย") !== false) {
                                ${"member" . $i . "_name"} = explode("นาย", ${"member" . $i . "_name"});
                                ${"member" . $i . "_name"} =  ${"member" . $i . "_name"}[1];
                                ${"member" . $i . "_prefix"} =  "นาย";
                            } else if (strpos(${"member" . $i . "_name"}, "นางสาว") !== false) {
                                ${"member" . $i . "_name"} = explode("นางสาว", ${"member" . $i . "_name"});
                                ${"member" . $i . "_name"} =  ${"member" . $i . "_name"}[1];
                                ${"member" . $i . "_prefix"} =  "นางสาว";
                            } else if (strpos(${"member" . $i . "_name"}, "นาง") !== false) {
                                ${"member" . $i . "_name"} = explode("นาง", ${"member" . $i . "_name"});
                                ${"member" . $i . "_name"} =  ${"member" . $i . "_name"}[1];
                                ${"member" . $i . "_prefix"} =  "นาง";
                            }
                        }
                    }

                    // echo "$member1_prefix $member1_name $member1_lastname";
                    // echo "$member2_prefix $member2_name $member2_lastname";
                    // echo "ชื่อ" . $member3_prefix . $member3_name . $member3_lastname;
                    // echo "<br>";

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
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "อาจารย์";
                        } else if (strpos($name_coAdvisor, "รองศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("รองศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "รองศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "รองศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("รองศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "รองศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ผู้ช่วยศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("ผู้ช่วยศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "ผู้ช่วยศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "ผู้ช่วยศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("ผู้ช่วยศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "ผู้ช่วยศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ศาสตราจารย์ ดร.") !== false) {
                            $name_coAdvisor = explode("ศาสตราจารย์ ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "ศาสตราจารย์ ดร.";
                        } else if (strpos($name_coAdvisor, "ศาสตราจารย์") !== false) {
                            $name_coAdvisor = explode("ศาสตราจารย์", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "ศาสตราจารย์";
                        } else if (strpos($name_coAdvisor, "ดร.") !== false) {
                            $name_coAdvisor = explode("ดร.", $name_coAdvisor);
                            $name_coAdvisor = explode(" ", $name_coAdvisor[1]);
                            $surname_coAdvisor = $name_coAdvisor[1];
                            $name_coAdvisor = $name_coAdvisor[0];
                            $prefix_coAdvisor = "ดร.";
                        }
                    } else {
                        $prefix_coAdvisor = '';
                        $name_coAdvisor = '';
                        $surname_coAdvisor = '';
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
                        $thesis_file_all = [];
                        $thesis_file = $thesis_file . ".pdf";
                        foreach ($file_ary as $file) {
                            // echo $file['name'];
                            if ($file['name'] == $thesis_file) {
                                $thesis_temp = $file['tmp_name'];
                                $thesis_name = $file['name'];
                                $thesis_upload_path = 'FileStorage/thesis/' . $thesis_name;
                                array_push($thesis_file_all, $thesis_upload_path);
                                // move_uploaded_file($thesis_temp, $thesis_upload_path);


                            } else {
                                $thesis_upload_path = '';
                            }
                        }
                    } else {
                        $thesis_file = "NULL";
                    }

                    // poster
                    if ($poster_file !== '') {
                        $poster_file_all = [];
                        $poster_file = $poster_file . ".pdf";
                        foreach ($file_ary as $file) {
                            // echo $file['name'];
                            if ($file['name'] == $poster_file) {
                                $poster_temp = $file['tmp_name'];
                                $poster_name = $file['name'];
                                $poster_upload_path = 'FileStorage/poster/' . $poster_name;
                                array_push($poster_file_all, $poster_upload_path);
                                // move_uploaded_file($poster_temp, $poster_upload_path);
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
                        $approval_file_all = [];
                        $approval_file = $approval_file . ".pdf";
                        foreach ($file_ary as $file) {
                            // echo $file['name'];
                            if ($file['name'] == $approval_file) {
                                $approval_temp = $file['tmp_name'];
                                $approval_name = $file['name'];
                                $approval_upload_path = 'FileStorage/approval/' . $approval_name;
                                // move_uploaded_file($approval_temp, $approval_upload_path);
                                array_push($approval_file_all, $approval_upload_path);
                            } else {
                                $approval_upload_path = '';
                                $approval_temp = '';
                            }
                        }
                    } else {
                        $poster_file = "NULL";
                    }
                    // echo $count-1;
                    // echo $poster_file_all[0] . "<br>";
                    // if(empty($approval_file_all)) {
                    //    echo "NULL";
                    // }
                    // echo $thesis_file_all[0];
                    // echo $poster_file_all[0];

                    // var_dump($name_coAdvisor);
                    // echo $name_coAdvisor . " " . $surname_coAdvisor;

                    $import_by = $_SESSION['prefix'] . $_SESSION['name'] . " " . $_SESSION['lastname'];

                    insertData(
                        $thai_name,
                        $english_name,
                        $abstract,
                        $printed_year,
                        $semester,
                        $approval_year,
                        $thesis_file_all[0],
                        $thesis_temp,
                        $approval_upload_path,
                        $approval_temp,
                        $poster_file_all[0],
                        $poster_temp,
                        $keywords,
                        $prefix_chairman,
                        $name_chairman[0],
                        $surname_chairman,
                        $prefix_director1,
                        $name_director1[0],
                        $surname_director1,
                        $prefix_director2,
                        $name_director2[0],
                        $surname_director2,
                        $prefix_advisor,
                        $name_advisor[0],
                        $surname_advisor,
                        $prefix_coAdvisor,
                        $name_coAdvisor,
                        $surname_coAdvisor,
                        $thesis_status,
                        $approval_status,
                        $member1_student_id,
                        $member1_prefix,
                        $member1_name,
                        $member1_lastname,
                        $member2_student_id,
                        $member2_prefix,
                        $member2_name,
                        $member2_lastname,
                        $member3_student_id,
                        $member3_prefix,
                        $member3_name,
                        $member3_lastname,
                        $import_by
                    );
                }
                $count++;
            }
            fclose($fileCSV);
        } else {
            // header("Location: thesisadd");
            echo "มีบางอย่างผิดพลาด ไม่ได้อัปโหลดไฟล์ CSV ";
            echo "<a href='thesisadd'>กลับหน้าเพิ่มปริญญานิพนธ์</a>";
            die();
        }
    } else {
        echo "มีบางอย่างผิดพลาด ";
        echo "<a href='thesisadd'>กลับหน้าเพิ่มปริญญานิพนธ์</a>";
        die();
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

    function insertData(
        $thai_name,
        $english_name,
        $abstract,
        $printed_year,
        $semester,
        $approval_year,
        $thesis_upload_path,
        $thesis_temp,
        $approval_upload_path,
        $approval_temp,
        $poster_upload_path,
        $poster_temp,
        $keywords,
        $prefix_chairman,
        $name_chairman,
        $surname_chairman,
        $prefix_director1,
        $name_director1,
        $surname_director1,
        $prefix_director2,
        $name_director2,
        $surname_director2,
        $prefix_advisor,
        $name_advisor,
        $surname_advisor,
        $prefix_coAdvisor,
        $name_coAdvisor,
        $surname_coAdvisor,
        $thesis_status,
        $approval_status,
        $member1_student_id,
        $member1_prefix,
        $member1_name,
        $member1_lastname,
        $member2_student_id,
        $member2_prefix,
        $member2_name,
        $member2_lastname,
        $member3_student_id,
        $member3_prefix,
        $member3_name,
        $member3_lastname,
        $import_by
    ) {
        require "dbconnect.php";

        try {
            $insert =  $conn->prepare("INSERT INTO thesis_document(thai_name, english_name, abstract, printed_year, semester, approval_year, thesis_file, approval_file, poster_file, keyword, prefix_chairman, name_chairman, surname_chairman, prefix_director1, name_director1, surname_director1, prefix_director2, name_director2, surname_director2, prefix_advisor, name_advisor, surname_advisor, prefix_coAdvisor, name_coAdvisor, surname_coAdvisor, thesis_status, approval_status, import_by)
        VALUES(:thai_name, :english_name, :abstract, :printed_year, :semester, :approval_year, :thesis_file, :approval_file, :poster_file, :keyword, :prefix_chairman, :name_chairman, :surname_chairman, :prefix_director1, :name_director1, :surname_director1, :prefix_director2, :name_director2, :surname_director2, :prefix_advisor, :name_advisor, :surname_advisor, :prefix_coAdvisor, :name_coAdvisor, :surname_coAdvisor, :thesis_status, :approval_status, :import_by) ");
            $insert->bindParam(":thai_name", $thai_name, PDO::PARAM_STR);
            $insert->bindParam(":english_name", $english_name, PDO::PARAM_STR);
            $insert->bindParam(":abstract", $abstract, PDO::PARAM_STR);
            $insert->bindParam(":printed_year", $printed_year, PDO::PARAM_STR);
            $insert->bindParam(":semester", $semester, PDO::PARAM_STR);
            $insert->bindParam(":approval_year", $approval_year, PDO::PARAM_STR);
            $insert->bindParam(":thesis_file", $thesis_upload_path, PDO::PARAM_STR);
            $insert->bindParam(":approval_file", $approval_upload_path, PDO::PARAM_STR);
            $insert->bindParam(":poster_file", $poster_upload_path, PDO::PARAM_STR);
            $insert->bindParam(":keyword", $keywords, PDO::PARAM_STR);
            $insert->bindParam(":prefix_chairman", $prefix_chairman, PDO::PARAM_STR);
            $insert->bindParam(":name_chairman", $name_chairman, PDO::PARAM_STR);
            $insert->bindParam(":surname_chairman", $surname_chairman, PDO::PARAM_STR);
            $insert->bindParam(":prefix_director1", $prefix_director1, PDO::PARAM_STR);
            $insert->bindParam(":name_director1", $name_director1, PDO::PARAM_STR);
            $insert->bindParam(":surname_director1", $surname_director1, PDO::PARAM_STR);
            $insert->bindParam(":prefix_director2", $prefix_director2, PDO::PARAM_STR);
            $insert->bindParam(":name_director2", $name_director2, PDO::PARAM_STR);
            $insert->bindParam(":surname_director2", $surname_director2, PDO::PARAM_STR);
            $insert->bindParam(":prefix_advisor", $prefix_advisor, PDO::PARAM_STR);
            $insert->bindParam(":name_advisor", $name_advisor, PDO::PARAM_STR);
            $insert->bindParam(":surname_advisor", $surname_advisor, PDO::PARAM_STR);
            $insert->bindParam(":prefix_coAdvisor", $prefix_coAdvisor, PDO::PARAM_STR);
            $insert->bindParam(":name_coAdvisor", $name_coAdvisor);
            $insert->bindParam(":surname_coAdvisor", $surname_coAdvisor, PDO::PARAM_STR);
            $insert->bindParam(":thesis_status", $thesis_status);
            $insert->bindParam(":approval_status", $approval_status);
            $insert->bindParam(":import_by", $import_by);

            $result = $insert->execute();
            if ($result) {
                // echo "เพิ่มข้อมูลเล่มสำเร็จ";'
                $lastId = $conn->lastInsertId();
                $thesisId = $lastId;
                try {
                    // echo "members";
                    if ($member1_student_id !== '') {
                        // echo "member 1";
                        $order = 1;
                        $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id, order_member)
                        VALUES(:student_id, :prefix, :name, :lastname, :thesis_id, :order_member)");
                        $insertMem->bindParam(":student_id", $member1_student_id);
                        $insertMem->bindParam(":prefix", $member1_prefix);
                        $insertMem->bindParam(":name", $member1_name);
                        $insertMem->bindParam(":lastname", $member1_lastname);
                        $insertMem->bindParam(":thesis_id", $thesisId);
                        $insertMem->bindParam(":order_member", $order);
                        $result = $insertMem->execute();
                    }
                    if ($member2_student_id != "") {
                        $order = 2;
                        $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id, order_member)
                        VALUES(:student_id, :prefix, :name, :lastname, :thesis_id, :order_member)");
                        $insertMem->bindParam(":student_id", $member2_student_id);
                        $insertMem->bindParam(":prefix", $member2_prefix);
                        $insertMem->bindParam(":name", $member2_name);
                        $insertMem->bindParam(":lastname", $member2_lastname);
                        $insertMem->bindParam(":thesis_id", $thesisId);
                        $insertMem->bindParam(":order_member", $order);
                        $result = $insertMem->execute();
                    }
                    if ($member3_student_id != "") {
                        $order = 3;
                        $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id, order_member)
                        VALUES(:student_id, :prefix, :name, :lastname, :thesis_id, :order_member)");
                        $insertMem->bindParam(":student_id", $member3_student_id);
                        $insertMem->bindParam(":prefix", $member3_prefix);
                        $insertMem->bindParam(":name", $member3_name);
                        $insertMem->bindParam(":lastname", $member3_lastname);
                        $insertMem->bindParam(":thesis_id", $thesisId);
                        $insertMem->bindParam(":order_member", $order);
                        $result = $insertMem->execute();
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                move_uploaded_file($poster_temp, $poster_upload_path);
                move_uploaded_file($thesis_temp, $thesis_upload_path);
                move_uploaded_file($approval_temp, $approval_upload_path);

                $_SESSION['countInsertSuccess']++;
                $_SESSION['InsertCsvSuccess'] = true;
                header('location: ./thesisadd');
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }
    ?>
