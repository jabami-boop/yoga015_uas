\### Laporan Bug



| No | File               | Baris     | Bug                             | Solusi                                                                 |

|----|--------------------|-----------|----------------------------------|------------------------------------------------------------------------|

| 1  | app/Controller/routes.php | 10  | Missing auth filter pada refresh endpoint | tambahkan `$routes->post('auth/refresh', 'AuthController::refresh', \['filter' => 'auth']);` |

| 2  | app/Controller/routes.php | 17  | Inconsistent API prefix         | Tambahkan semua endpoint API menggunakan prefix yang konsisten, misal `api/` |

| 3  | app/Controller/routes.php | 34  | Wrong filter name untuk tasks   | Pastikan filter yang digunakan pada route tasks sesuai dengan nama filter yang ada, `'auth'` |

| 4  | database.php              | 78  | Database might not exist        | Tambahkan `parent::\_\_construct();` |

| 5  | adatabase.php             | 56-76| Missing test database config    | Tambahkan konfigurasi untuk testing |

| 6  | AuthController.php        | 34  | No input validation pada register | Tambahkan validasi input pada method register |

| 7  | AuthController.php        | 46  | Password tidak di-hash          | tambahkan `'password' => password\_hash($this->request->getVar('password'), PASSWORD\_DEFAULT)` |



