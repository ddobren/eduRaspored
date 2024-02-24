-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2024 at 07:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduraspored`
--

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `class_info` varchar(255) NOT NULL,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`schedule`)),
  `user_token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetables`
--

INSERT INTO `timetables` (`id`, `school_id`, `name`, `place`, `class_info`, `schedule`, `user_token`) VALUES
(98, 43, 'EKONOMSKA I TRGOVAČKA ŠKOLA - Dubrovnik', 'Dubrovnik', '1.D - Web dizajner', '{\"subjects\":[{\"Ponedjeljak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Hrv. J.\"},{\"Predmet 2\":\"Hrv. J.\"},{\"Predmet 3\":\"G1 RAČ. GRAF. ili G2 WEB. PROJ\"},{\"Predmet 4\":\"G1 RAČ. GRAF. ili G2 WEB. PROJ\"},{\"Predmet 5\":\"Matematika\"},{\"Predmet 6\":\"Vjeronauk\"}]},{\"Utorak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Fizika\"},{\"Predmet 2\":\"Fizika\"},{\"Predmet 3\":\"Eng. J.\"},{\"Predmet 4\":\"Int. Tehnologije\"},{\"Predmet 5\":\"Hrv. J.\"},{\"Predmet 6\":\"Povijest\"},{\"Predmet 7\":\"Sat razrednika\"}]},{\"Srijeda\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Matematika\"},{\"Predmet 2\":\"Biologija\"},{\"Predmet 3\":\"Biologija\"},{\"Predmet 4\":\"Informatika\"},{\"Predmet 5\":\"Lik. Umjetnost\"},{\"Predmet 6\":\"Lik. Umjetnost\"}]},{\"Četvrtak\":[{\"Predmet 0\":\"Inf. Klub\"},{\"Predmet 1\":\"Matematika\"},{\"Predmet 2\":\"Hrv. J.\"},{\"Predmet 3\":\"G1 RAČ. GRAF. ili G2 WEB. PROJ\"},{\"Predmet 4\":\"G1 RAČ. GRAF. ili G2 WEB. PROJ\"},{\"Predmet 5\":\"Eng. J.\"},{\"Predmet 6\":\"Povijest\"}]},{\"Petak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Geografija\"},{\"Predmet 2\":\"Geografija\"},{\"Predmet 3\":\"Informatika\"},{\"Predmet 4\":\"Eng. J.\"},{\"Predmet 5\":\"Int. Tehnologije\"},{\"Predmet 6\":\"Int. Tehnologije\"}]},{\"Subota\":[{\"Predmet 0\":\"-\"}]},{\"Nedjelja\":[{\"Predmet 0\":\"-\"}]}]}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTUsInVzZXJuYW1lIjoiZG9icmVuIiwiZW1haWwiOiJkb2JyZW5AZ21haWwuY29tIiwiZXhwIjoxNzA4MzgwMjU1fQ.8eAJoBxKo3Tu2CfSnK3wVDzTQeuDE_PT033fsl_4jTs'),
(107, 43, 'EKONOMSKA I TRGOVAČKA ŠKOLA - Dubrovnik', 'Dubrovnik', '1.B - Upravni referent', '{\"subjects\":[{\"Ponedjeljak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Hrv. J.\"},{\"Predmet 2\":\"Hrv. J.\"},{\"Predmet 3\":\"Povijest\"},{\"Predmet 4\":\"Povijest\"},{\"Predmet 5\":\"Eng. J.\"},{\"Predmet 6\":\"G1 KOMP. DAKTILOGRAFIJA\"},{\"Predmet 7\":\"G1 KOMP. DAKTILOGRAFIJA\"}]},{\"Utorak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Zemljopis\"},{\"Predmet 2\":\"Zemljopis\"},{\"Predmet 3\":\"Uvod u državu i pravo\"},{\"Predmet 4\":\"Uvod u državu i pravo\"},{\"Predmet 5\":\"Matematika\"},{\"Predmet 6\":\"G2 KOMP. DAKTILOGRAFIJA\"},{\"Predmet 7\":\"G2 KOMP. DAKTILOGRAFIJA\"}]},{\"Srijeda\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"Čovjek, zdravlje i okoliš\"},{\"Predmet 2\":\"Hrv. J.\"},{\"Predmet 3\":\"Hrv. J.\"},{\"Predmet 4\":\"Čovjek, zdravlje i okoliš\"},{\"Predmet 5\":\"G1 i G2 INFORMATIKA\"},{\"Predmet 6\":\"G1 KOMP. DAKTILOGRAFIJA ili G2 INFORMATIKA\"},{\"Predmet 7\":\"Sat razrednika\"}]},{\"Četvrtak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"TALIJANSKI ili NJEMAČKI\"},{\"Predmet 2\":\"TALIJANSKI ili NJEMAČKI\"},{\"Predmet 3\":\"Matematika\"},{\"Predmet 4\":\"Eng. J.\"},{\"Predmet 5\":\"Matematika\"},{\"Predmet 6\":\"G1 i G2 KOMP. DAKTILOGRAFIJA\"},{\"Predmet 7\":\"Sat razrednika\"}]},{\"Petak\":[{\"Predmet 0\":\"-\"},{\"Predmet 1\":\"LATINSKI\"},{\"Predmet 2\":\"LATINSKI\"},{\"Predmet 3\":\"Hrvatski poslovni jezik\"},{\"Predmet 4\":\"Uvod u državu i pravo\"},{\"Predmet 5\":\"Eng. J.\"},{\"Predmet 6\":\"G1 INFORMATIKA ili G2 KOMP. DAKTILOGRAFIJA\"},{\"Predmet 7\":\"Vjeronauk\"}]},{\"Subota\":[{\"Predmet 0\":\"-\"}]},{\"Nedjelja\":[{\"Predmet 0\":\"-\"}]}]}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTUsInVzZXJuYW1lIjoiZG9icmVuIiwiZW1haWwiOiJkb2JyZW5AZ21haWwuY29tIiwiZXhwIjoxNzA4NTYwNTQwfQ.jC1UIhLAwjOmFoyQ8B2phGVjtX2hxZeBEY1n-buvWUA'),
(113, 371, 'TEHNIČKA ŠKOLA RUĐERA BOŠKOVIĆA - Zagreb', 'Zagreb', '1.C', '{  \"subjects\": [    {      \"Ponedjeljak\": [        {          \"Predmet 0\": \"ghdgchncgn\"        }      ]    },    {      \"Utorak\": [        {          \"Predmet 0\": \"hdfczhdfhdg\"        }      ]    },    {      \"Srijeda\": [        {          \"Predmet 0\": \"-\"        },        {          \"Predmet 1\": \"dtzhdtzhd\"        },        {          \"Predmet 2\": \"trhdrthdrth\"        },        {          \"Predmet 3\": \"drthdrthz\"        },        {          \"Predmet 4\": \"tzdjhdtzh\"        }      ]    },    {      \"Četvrtak\": [        {          \"Predmet 0\": \"tdzjdtzjdtzh\"        },        {          \"Predmet 1\": \"fzujdtzhdtz\"        },        {          \"Predmet 2\": \"jftgzjdtzj\"        }      ]    },    {      \"Petak\": [        {          \"Predmet 0\": \"thsrthdrthdtrh\"        }      ]    },    {      \"Subota\": [        {          \"Predmet 0\": \"-\"        }      ]    },    {      \"Nedjelja\": [        {          \"Predmet 0\": \"-\"        }      ]    }  ]}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTUsInVzZXJuYW1lIjoiZG9icmVuIiwiZW1haWwiOiJkb2JyZW5AZ21haWwuY29tIiwiZXhwIjoxNzA4NjQ4MTA3fQ.zxeKBU47k_iFcK2iY8FgqZs3Sv3Dfg2fNlbOzM4fCBg'),
(114, 371, 'TEHNIČKA ŠKOLA RUĐERA BOŠKOVIĆA - Zagreb - Zagreb', 'Zagreb', '2.B', '{  \"subjects\": [    {      \"Ponedjeljak\": [        {          \"Predmet 0\": \"dfgsdfgs\"        }      ]    },    {      \"Utorak\": [        {          \"Predmet 0\": \"-\"        },        {          \"Predmet 1\": \"xcfdgxdfgsydfg\"        }      ]    },    {      \"Srijeda\": [        {          \"Predmet 0\": \"dfgydfgydfg\"        },        {          \"Predmet 1\": \"dfgsdfgydfg\"        },        {          \"Predmet 2\": \"sfdgsxfdgs\"        }      ]    },    {      \"Četvrtak\": [        {          \"Predmet 0\": \"-\"        },        {          \"Predmet 1\": \"bxfgncghn\"        },        {          \"Predmet 2\": \"gchnsdxfthsxft\"        },        {          \"Predmet 3\": \"gcmjcgzhxf\"        }      ]    },    {      \"Petak\": [        {          \"Predmet 0\": \"cghnxfgxftgb\"        },        {          \"Predmet 1\": \"jmcghndxfghxnfg\"        },        {          \"Predmet 2\": \"gfxxfgnxfgn\"        }      ]    },    {      \"Subota\": [        {          \"Predmet 0\": \"-\"        }      ]    },    {      \"Nedjelja\": [        {          \"Predmet 0\": \"-\"        }      ]    }  ]}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTUsInVzZXJuYW1lIjoiZG9icmVuIiwiZW1haWwiOiJkb2JyZW5AZ21haWwuY29tIiwiZXhwIjoxNzA4NjQ4MTA3fQ.zxeKBU47k_iFcK2iY8FgqZs3Sv3Dfg2fNlbOzM4fCBg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `token` text NOT NULL,
  `token_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `token`, `token_active`) VALUES
(15, 'dobren', 'dobren@gmail.com', '$2y$10$ihdhUu5djTvULV4upbGvo.c7CxrK08xsyCa0c9M9TrjhMMszBZ23.', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTUsInVzZXJuYW1lIjoiZG9icmVuIiwiZW1haWwiOiJkb2JyZW5AZ21haWwuY29tIiwiZXhwIjoxNzA4NzM4Nzk5fQ.2jYla3IbEpGpqwudGdxDIpNtJy1i_Y1AarOO-O-YmQk', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
