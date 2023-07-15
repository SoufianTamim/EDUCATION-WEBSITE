-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2023 at 03:15 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `educa`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`user_id`, `playlist_id`) VALUES
('tkH36WtLpxeKLq8feT8Z', 'hyKZP7VkBA7QXNhhjm4i'),
('tkH36WtLpxeKLq8feT8Z', '3'),
('1FOIMWqPlVH1kxMrhvZt', 'hyKZP7VkBA7QXNhhjm4i'),
('1FOIMWqPlVH1kxMrhvZt', '3'),
('1FOIMWqPlVH1kxMrhvZt', '4'),
('1FOIMWqPlVH1kxMrhvZt', '5'),
('1FOIMWqPlVH1kxMrhvZt', '6'),
('1FOIMWqPlVH1kxMrhvZt', '7'),
('1FOIMWqPlVH1kxMrhvZt', '8');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `user_id`, `comment`, `date`) VALUES
('lHhepyI3uv8sxL6whEWK', '8', 'tkH36WtLpxeKLq8feT8Z', 'this is a comment from kim to the teacher of this course \r\nyou are amazing', '2023-02-02 15:13:39'),
('xjQCcCw6wBt4YvU6i7AD', '7', 'tkH36WtLpxeKLq8feT8Z', 'this is a comment from kim to the teacher of this course \r\nyou are amazing', '2023-02-02 15:14:00'),
('yRaUMcZe3Sj0rjc8CszJ', '9', 'tkH36WtLpxeKLq8feT8Z', 'this is a comment from kim to the teacher of this course \r\nyou are amazing', '2023-02-02 15:14:07'),
('iZGPlqJAmFuLDRns6HId', 'Jnmuo3Ikw93otAxLwotW', 'tkH36WtLpxeKLq8feT8Z', 'comment from kim weksler', '2023-02-06 13:51:03'),
('gJjKv6qEahSPLUaWa5pI', 'Jnmuo3Ikw93otAxLwotW', 'tkH36WtLpxeKLq8feT8Z', 'this is an other comment', '2023-02-06 14:48:52'),
('LYOTlpUm74g0CumJfPy2', 'Nx46joVwpFUV3qjLTyHk', '1FOIMWqPlVH1kxMrhvZt', 'this is a comment from hasnaa', '2023-02-06 14:49:43'),
('Ak1lRmQPz6FqPadUdscR', '8', 'tkH36WtLpxeKLq8feT8Z', 'انا الانجليزي يابتوع التعليم المجاني', '2023-02-06 15:10:05'),
('0zcKFPdIgPhTSxKHztJl', 'Jnmuo3Ikw93otAxLwotW', 'tkH36WtLpxeKLq8feT8Z', 'الهم بارك احلي كورس ده ولا اي', '2023-02-06 15:16:14'),
('bzHgrPMRZC2gq1NfZd64', '7', '1FOIMWqPlVH1kxMrhvZt', 'this is a comment from hasnaa ', '2023-02-06 16:12:43'),
('4cFAws2hiWH1Z6wg8tNq', 'Jnmuo3Ikw93otAxLwotW', '1FOIMWqPlVH1kxMrhvZt', 'this is a comment from hasnaa ', '2023-02-06 16:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `message` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`name`, `email`, `number`, `message`) VALUES
('abdo goda', 'abdo@gmail.com', 1241645846, 'this is a message'),
('thia queen', 'thia@gmail.com', 2147483647, 'this is a message');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` varchar(20) DEFAULT NULL,
  `teacher_id` varchar(20) DEFAULT NULL,
  `playlist_id` varchar(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `video` varchar(100) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `teacher_id`, `playlist_id`, `title`, `description`, `video`, `thumb`, `date`, `status`) VALUES
('7', 'd0AStGHGz8AtnHZgDsGJ', '3', 'complete JS course', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis, quod? Nihil earum hic veniam provident, fugiat adipisci architecto modi tempore.', 'vid-7.mp4', 'post-3-1.PNG', '2023-02-01 14:42:32', 'active'),
('8', 'd0AStGHGz8AtnHZgDsGJ', '3', 'complete JS course', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis, quod? Nihil earum hic veniam provident, fugiat adipisci architecto modi tempore.', 'vid-8.mp4', 'post-3-2.PNG', '2023-02-01 14:42:32', 'active'),
('9', 'd0AStGHGz8AtnHZgDsGJ', '3', 'complete JS course', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis, quod? Nihil earum hic veniam provident, fugiat adipisci architecto modi tempore.', 'vid-9.mp4', 'post-3-3.PNG', '2023-02-01 14:42:32', 'active'),
('Nx46joVwpFUV3qjLTyHk', 'd0AStGHGz8AtnHZgDsGJ', '4', 'complete bootstrap course (part 03)', 'this is the third course in playlist called bootstrap tutorial \r\nthe aim of this  course to practice of the css course and make a front end website in shorter time', NULL, 'AVNUEpSE0fssrqXxUv4b.png', '2023-02-05 23:20:13', 'active'),
('Jnmuo3Ikw93otAxLwotW', 'd0AStGHGz8AtnHZgDsGJ', '', 'Jquery course', 'this is the first course to learn Jquery ', NULL, 'NhDhSii0SniqTmwCHMas.png', '2023-02-05 23:22:13', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) DEFAULT NULL,
  `content_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user_id`, `content_id`) VALUES
('tkH36WtLpxeKLq8feT8Z', '7'),
('tkH36WtLpxeKLq8feT8Z', 'Jnmuo3Ikw93otAxLwotW'),
('1FOIMWqPlVH1kxMrhvZt', '7'),
('1FOIMWqPlVH1kxMrhvZt', 'Jnmuo3Ikw93otAxLwotW'),
('1FOIMWqPlVH1kxMrhvZt', 'Nx46joVwpFUV3qjLTyHk');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` varchar(20) DEFAULT NULL,
  `teacher_id` varchar(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `teacher_id`, `title`, `description`, `thumb`, `date`, `status`) VALUES
('3', 'd0AStGHGz8AtnHZgDsGJ', 'complete JS course', 'this is an js course playlist', 'thumb-3.PNG', '2023-02-01 14:29:40', 'active'),
('4', 'd0AStGHGz8AtnHZgDsGJ', 'complete BOOTSTRAP course', 'this is an bootstrap course playlist', 'thumb-4.PNG', '2023-02-01 14:29:40', 'active'),
('5', 'csIKqzUQlYa76UvOMnqk', 'complete SASS course', 'this is an jquery course playlist', 'thumb-5.PNG', '2023-02-01 14:29:40', 'active'),
('6', 'csIKqzUQlYa76UvOMnqk', 'complete JQUERY course', 'this is an sass course playlist', 'thumb-6.PNG', '2023-02-01 14:29:40', 'active'),
('7', '6yd84j6gfGMzS9JzMVBi', 'complete PHP course', 'this is an php course playlist', 'thumb-7.PNG', '2023-02-01 14:29:40', 'active'),
('8', 'uKodjfuRDo29D6uQnC6A', 'complete MYSQL course', 'this is an mysql course playlist', 'thumb-8.PNG', '2023-02-01 14:29:40', 'active'),
('9', '7CauQA91MWr1OdJLsrOI', 'complete REACT course', 'this is an react course playlist', 'thumb-9.PNG', '2023-02-01 14:29:40', 'active'),
('k5WQA3k0kf3OqbhRSX7i', 'csIKqzUQlYa76UvOMnqk', 'MYSQL course', 'this playlist to learn how to code mysql in php and deal with databases and tables in order to create a real application', 'VArNCYvYUlDpqRmuWrR7.png', '2023-02-04 16:10:02', 'deactive'),
('hyKZP7VkBA7QXNhhjm4i', '2JNk7nqr3rpg7tqgs9Fn', 'ADVANCED HTML TUTORIAL', 'this course is not for Beginners in html or front end web developers.\r\ni made this course for the intermediate in html', 'RlC7MnUiunXL650HapFm.png', '2023-02-04 17:40:59', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` VARCHAR(20)  PRIMARY KEY ,
  `name` varchar(50) DEFAULT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `profession`, `email`, `password`, `image`) VALUES
('2JNk7nqr3rpg7tqgs9Fn', 'teacher_1', 'front end developer', 'teacher_1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'JEk5pkzXRDv0Dhnnt6Ny.jfif'),
('d0AStGHGz8AtnHZgDsGJ', 'teacher_2', 'back end developer', 'teacher_2@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'JTnOJGstDysNPijR2az3.jpg'),
('csIKqzUQlYa76UvOMnqk', 'teacher_3', 'accountant', 'teacher_3@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'UQNyyV82TdnfF98W3oQe.jfif'),
('6yd84j6gfGMzS9JzMVBi', 'teacher_4', 'designer', 'teacher_4@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'vSBRXOwdO3rbKmYIKubX.jpg'),
('uKodjfuRDo29D6uQnC6A', 'teacher_5', 'programmer', 'teacher_5@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Yg5Y3rxwmjrPGIFCUDEv.jfif'),
('7CauQA91MWr1OdJLsrOI', 'teacher_6', 'designer', 'teacher_6@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '7Txv3ScTbxo2Jhm8XKrD.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) PRIMARY KEY ,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `image`) VALUES
('lLwW2XTuxGo3QCe9nUyS', 'thia queen', '01216345469', 'thia@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'sVn8iR3OJp0hhvtNcGgO.jpg'),
('tkH36WtLpxeKLq8feT8Z', 'kim weksler', '01231545646', 'kim@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'dFUWw683QPCMUnjePO6R.png'),
('6F1MNx1KZ6QVnVsPoIag', 'jim halpert', '01012123456', 'jim@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2dwa7wnrPiNvEaKRbLOi.png'),
('s4QGasZyJHRxyZlrtxv0', 'john snow', '01224564754', 'john@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'GLIdB0a3Uk4f0CNQwUhh.png'),
('I0xjJORR0Akw2vRBS8KR', 'user1', '01215455445', 'user1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', ''),
('1FOIMWqPlVH1kxMrhvZt', 'hasnaa', '01264684758', 'hasnaa@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Ak5dECacAY92lCuUi6VO.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
