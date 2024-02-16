-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2024 at 06:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `streamflix`
--

-- --------------------------------------------------------

--
-- Table structure for table `actor`
--

CREATE TABLE `actor` (
  `actor_id` int NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `actor`
--

INSERT INTO `actor` (`actor_id`, `nickname`, `first_name`, `last_name`) VALUES
(1, 'The Rock', 'Dwayne', 'Johnson'),
(2, 'Dylan Sprouse', 'Dylan', 'Sprouse'),
(4, 'Christoph Waltz', 'Christoph', 'Waltz'),
(5, 'Leonardo DiCaprio', 'Leonardo', 'Wilhelm DiCaprio'),
(6, 'Kate Winslet', 'Kate', 'Elizabeth Winslet'),
(7, 'Johnny Depp', 'John', 'Christopher Depp II'),
(8, 'Robert De Niro', 'Robert', 'Anthony De Niro'),
(9, 'Thom Cruise', 'Thomas', 'Cruise Mapother IV'),
(10, 'Matt Damon', 'Matthew', 'Paige Damon'),
(11, 'Martin Scorsese', 'Martin', 'Charles Scorsese'),
(12, 'Angelina Jolie', 'Angelina', 'Jolie'),
(13, 'Tobey Maguire', 'Tobias', 'Vincent Maguire'),
(14, 'Vittoria Ceretti', 'Vittoria', 'Ceretti'),
(15, 'Victoria Pedretti', 'Victoria', 'Pedretti');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `content_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_year` date NOT NULL,
  `duration` int NOT NULL,
  `description` text,
  `cast` json DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `content_type` enum('movie','series','tv_show') NOT NULL,
  `video_embed` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`content_id`, `title`, `release_year`, `duration`, `description`, `cast`, `cover_image`, `content_type`, `video_embed`) VALUES
(1, 'The Irishman', '2022-10-30', 90, 'MOB', '[\"Christoph Waltz\", \"Robert De Niro\", \"Vittoria Ceretti\"]', '/assets/img/cover_image/598d86c3a9ea79a5309bb7719a7cef96.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/WHXxVmeGQUc?si=c8NmjjH0GWPlLvHQ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(2, 'Halo', '2022-09-22', 50, 'In a futuristic world, human beings must put up a united front in order to stop powerful aliens from taking over and destroying humanity.', '[\"Johnny Depp\", \"Angelina Jolie\", \"Tobey Maguire\", \"Vittoria Ceretti\", \"Victoria Pedretti\"]', '/assets/img/cover_image/4b7b11a1f4196115256650137a006e40.jpg', 'series', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/5KZ3MKraNKY?si=Cpgf8pFu4FlN3w1s\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(3, 'Dune', '2024-02-10', 60, 'Coming soon', '[\"Dylan Sprouse\", \"Angelina Jolie\", \"Victoria Pedretti\"]', '/assets/img/cover_image/6d80ee4b2a3a7bc622c43758576bd71a.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/U2Qp5pL3ovA?si=SiRA_2xebgojmhbl\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(4, 'One Piece', '2023-05-18', 150, 'One piece live action', '[\"Johnny Depp\", \"Robert De Niro\", \"Angelina Jolie\", \"Tobey Maguire\", \"Vittoria Ceretti\", \"Victoria Pedretti\"]', '/assets/img/cover_image/253b7b9902134d9467d5294515ff15a8.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/Ades3pQbeh8?si=CW2oIJY05qJgskhf\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(5, 'Mefira', '2024-02-15', 120, 'Good movie', '[\"Dylan Sprouse\", \"Leonardo DiCaprio\", \"Kate Winslet\"]', '/assets/img/cover_image/41e72784467f52597890f49623631423.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/ApLYzh5el0c?si=hIy7JuxT7h0pv465\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(6, 'Napoleon', '2024-02-14', 180, 'I don\'t know', '[\"The Rock\", \"Christoph Waltz\", \"Johnny Depp\"]', '/assets/img/cover_image/ccfde0983aa8ae68d27293bf64c889ab.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/OAZWXUkrjPc?si=qHxGun3RLra0854l\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(8, 'Peaky Blinder', '2012-08-15', 50, 'British series', '[\"Dylan Sprouse\", \"Christoph Waltz\"]', '/assets/img/cover_image/bff88ee5ed3dfec5de874e791e6245b7.jpg', 'series', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/oVzVdvGIC7U?si=C1fPfiKt6rhfBmt-\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(9, 'The Office', '2005-01-07', 1234, 'The show documents the exploits of a paper supply company in Scranton, Pennsylvania. With an office including the likes of various peers, this series takes a look at the lives of its co-workers.', '[\"The Rock\", \"Dylan Sprouse\", \"Leonardo DiCaprio\", \"Kate Winslet\", \"Robert De Niro\", \"Matt Damon\", \"Victoria Pedretti\"]', '/assets/img/cover_image/25a140ea7fda459f7e09e5acdc69d9b6.jpg', 'tv_show', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/tNcDHWpselE?si=3hqQbXN1POCxZahk\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(10, 'Breaking Bad', '2009-05-18', 75, 'Most watched series in the world', '[\"Leonardo DiCaprio\", \"Kate Winslet\", \"Thom Cruise\", \"Angelina Jolie\"]', '/assets/img/cover_image/62dfd8df03f1664f4ac51af292125c62.jpg', 'series', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/ceqOTZnhgY8?si=dJ6RnYKmhL52nc4y\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(11, 'Better Call Saul', '2021-10-15', 110, 'Prequel of Breaking Bad', '[\"Matt Damon\", \"Victoria Pedretti\"]', '/assets/img/cover_image/92d8f7397b25248b23ed7b53fc6db152.jpg', 'series', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/HN4oydykJFc?si=cG5DTQ6nOOPLj7eO\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(12, 'You', '2015-05-19', 50, 'Psycho Romance', NULL, '/assets/img/cover_image/64030adccd4c2428051592b4474dc031.jpg', 'series', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/ga1m0wjzscU?si=Fg_qJf9p2f-eF-mQ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(13, 'Family Guy', '1999-01-13', 24, 'Peter Griffin tries to do the right thing for his assorted family of two teenagers, a smart dog and a devilish baby. Despite his good intentions, Peter ends up making hilarious mistakes all the time.', '[\"The Rock\", \"Christoph Waltz\", \"Kate Winslet\", \"Vittoria Ceretti\"]', '/assets/img/cover_image/fe4ba91860bf988595f39bdabc012c60.jpg', 'tv_show', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/qMAZYfwEJ6k?si=Q4E844YlNDQs07n2\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(14, 'Wonka', '2023-10-30', 120, 'Based on the extraordinary character at the center of Charlie and the Chocolate Factory, Roald Dahl’s most iconic children’s book and one of the best-selling children’s books of all time, “Wonka” tells the wondrous story of how the world’s greatest inventor, magician and chocolate-maker became the beloved Willy Wonka we know today.\r\n\r\nFrom Paul King, writer/director of the “Paddington” films, David Heyman, producer of “Harry Potter,” “Gravity,” “Fantastic Beasts” and “Paddington,” and producers Alexandra Derbyshire (the “Paddington” films, “Jurassic World: Dominion”) and Luke Kelly (“Roald Dahl’s The Witches”), comes an intoxicating mix of magic and music, mayhem and emotion, all told with fabulous heart and humor. Starring Timothée Chalamet in the title role, this irresistibly vivid and inventive big screen spectacle will introduce audiences to a young Willy Wonka, chock-full of ideas and determined to change the world one delectable bite at a time—proving that the best things in life begin with a dream, and if you’re lucky enough to meet Willy Wonka, anything is possible.\r\n\r\nStarring alongside Chalamet are Calah Lane (“The Day Shall Come”), Emmy and Peabody Award winner Keegan-Michael Key (“The Prom,” “Schmigadoon”), Paterson Joseph (“Vigil,” “Noughts + Crosses”), Matt Lucas (“Paddington,” “Little Britain”), Mathew Baynton (“The Wrong Mans,” “Ghosts”), Oscar nominee Sally Hawkins (“The Shape of Water,” the “Paddington” films, “Spencer”), Rowan Atkinson (the “Johnny English” and “Mr. Bean” films, “Love Actually”), Jim Carter (“Downton Abbey”), with Oscar winner Olivia Colman (“The Favourite”). The film also stars Natasha Rothwell (“White Lotus,” “Insecure”), Rich Fulcher (“Marriage Story,” “Disenchantment”), Rakhee Thakrar (“Sex Education,” “Four Weddings and a Funeral”), Tom Davis (“Paddington 2,” “King Gary”) and Kobna Holdbrook-Smith (“Paddington 2,” “Zack Snyder’s Justice League,” “Mary Poppins Returns”).\r\n\r\nSimon Farnaby (“Paddington 2”) & Paul King wrote the screenplay, based on a story by King and characters created by Roald Dahl. Michael Siegel, Cate Adams, Rosie Alison and Tim Wellspring are serving as executive producers. King’s behind-the-scenes creative team includes director of photography Chung-Hoon Chung (“Last Night in Soho,” “Ah-ga-ssi”); Oscar-nominated production designer Nathan Crowley (“Tenet,” “Dunkirk”); editor Mark Everson (the “Paddington” films); Oscar-winning costume designer Lindy Hemming (the “Paddington” films, “Topsy-Turvy”); and composer Joby Talbot (the “Sing” films). Neil Hannon of the band The Divine Comedy is writing original songs for the film.\r\n\r\nWarner Bros. Pictures Presents, in Association with Village Roadshow Pictures, a Heyday Films Production, a Paul King Confection, “Wonka,” set to open in theaters and in IMAX internationally beginning in December 2023 and in North America on December 15, 2023; it will be distributed worldwide by Warner Bros. Pictures.', NULL, '/assets/img/cover_image/ed61bb2afef95b7511f4c118c2cbe54e.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/otNh9bTjXWg?si=OiM64hHTg0WYZWe-\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(15, 'Solace', '2015-06-19', 126, 'When FBI Special Agent Joe Merriwether (Jeffrey Dean Morgan) is unable to solve a series of homicides, he decides to enlist the help of his former colleague Dr. John Clancy (Anthony Hopkins), a retired physician with psychic powers. The reclusive Clancy, who shuttered his practice and retreated from the world following the death of his daughter and subsequent break-up of his marriage, wants nothing to do with the case. He soon changes his mind after seeing disturbingly violent visions of Joe’s partner, FBI Special Agent Katherine Cowles’s (Abbie Cornish) ultimate demise. When Clancy’s exceptional intuitive powers put him on the trail of a suspect, Charles Ambrose (Colin Farrell), the doctor soon realizes his abilities are no match against the extraordinary powers of this vicious murderer on a mission.', NULL, '/assets/img/cover_image/7d7e41e4957524dea20c6582b234c475.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/kEu7BS8IsuA?si=ZAMyRvHGXztHu_n9\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(16, 'The New Look', '2024-02-10', 151, 'First movie trailer for The new look starring Juliette Binoche, Ben Mendelsohn.', '[\"Christoph Waltz\"]', '/assets/img/cover_image/2c9a5f76e116d26dc8605f83d35b90fc.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/akFA7vVnd1E?si=uCouzMi6ShFaNAba\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(17, 'Love Between Fairy and Devil', '2023-05-17', 49, 'The Wulan clan was exterminated by the demon god Dong Fang Qing Cang, and after ten thousand years, the goddess rebirths as a low-ranking immortal girl in the heavenly realm, and accidentally revived the demon god who trapped her clan in the Haotian Tower. In order to gain freedom, Dong Fang Qing Cang has to sacrifice the female soul of the orchid to unlock the spell seal on her body. In the process, the devil, who has broken his love, falls in love with a gentle and cute little monster.', NULL, '/assets/img/cover_image/64e750de94f8be89c100591afb6b7618.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/HrUeOw7zL40?si=lBREV34IoMkxwrS_\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(18, 'Maradona: Blessed Dream', '2021-10-19', 107, 'Maradona: Blessed Dream follows the life of legendary footballer Diego Maradona, through the highs and lows of his career. Watch Maradona: Blessed Dream on Prime Video from 29th October.', NULL, '/assets/img/cover_image/0222b5d73e0ffb3acb4eb269238ecdf1.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/FY2lrrL48ps?si=v5ZP94eFNAO5ocEU\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(19, 'Ferrari', '2024-01-17', 119, 'First movie trailer for Ferrari starring Adam Driver, Penélope Cruz, Shailene Woodley.', NULL, '/assets/img/cover_image/c9fcd33297177a024fe241e02059c3eb.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/TdJoLE_R8Uk?si=5NTbdkNY42RaxEP8\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(20, 'Deadpool & Wolverine', '2024-07-26', 116, '', NULL, '/assets/img/cover_image/4e3f8a8d04e540448a6d0ebcfe936853.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/uJMCNJP2ipI?si=wd6DOMZ91iMc-Lj-\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(21, 'Wish', '2023-10-01', 109, 'Revealed today are additional members of the film’s voice cast, who join previously announced cast members Ariana DeBose, who voices 17-year-old heroine Asha; Chris Pine, who lends his voice to the formidable King Magnifico; and Alan Tudyk, who provides the memorable voice of Asha’s pet goat, Valentino. \r\n\r\nCast members include:\r\nAngelique Cabral as Queen Amaya, the wife and sounding board of King Magnifico; Victor Garber as Asha’s grandfather, Sabino, who—at 100 years old—is patiently waiting for his wish to be granted; and Natasha Rothwell as Asha’s loving and supportive mom, Sakina.\r\n \r\nPlus, Asha’s tight-knit group of confidants, protectors and forever friends: Jennifer Kumiyama as Asha’s dearest friend, Dahlia, who’s an accomplished baker and unofficial leader of their group; Evan Peters as the strong guy with a big heart and bigger yawn, Simon; Harvey Guillén as Gabo, who may be cynical, but he has a heart of gold; Ramy Youssef as Safi, who’s plagued by allergies; Niko Vargas as Asha’s joyful, always smiling buddy, Hal; Della Saba as the seemingly shy teenager, Bazeema, who’s full of surprises; and Jon Rudnitsky as Asha’s rosy-cheeked, wiggly-eared pal, Dario.\r\n \r\nABOUT THE MOVIE\r\n\r\nWalt Disney Animation Studios’ “Wish” is an all-new musical-comedy welcoming audiences to the magical kingdom of Rosas, where Asha, a sharp-witted idealist, makes a wish so powerful that it is answered by a cosmic force—a little ball of boundless energy called Star. Together, Asha and Star confront a most formidable foe—the ruler of Rosas, King Magnifico—to save her community and prove that when the will of one courageous human connects with the magic of the stars, wondrous things can happen. Featuring the voices of Academy Award®-winning actor Ariana DeBose as Asha, Chris Pine as Magnifico, and Alan Tudyk as Asha’s favorite goat, Valentino, the film is helmed by Oscar®-winning director Chris Buck (“Frozen,” “Frozen 2”) and Fawn Veerasunthorn (“Raya and the Last Dragon”), and produced by Peter Del Vecho (“Frozen,” “Frozen 2”) and Juan Pablo Reyes Lancaster Jones (“Encanto”). Jennifer Lee (“Frozen,” “Frozen 2”) executive produces—Lee and Allison Moore (“Night Sky,” “Manhunt”) are writers on the project. With original songs by Grammy®-nominated singer/songwriter Julia Michaels and Grammy-winning producer/songwriter/musician Benjamin Rice, plus score by composer Dave Metzger, “Wish” opens only in theaters on Nov. 22, 2023.', NULL, '/assets/img/cover_image/f397e9cb7632316a39ebdbc488eb1b62.jpg', 'movie', '<iframe width=\"360\" height=\"215\" src=\"https://www.youtube.com/embed/oyRxxpD3yNw?si=Zd42UyPleYI_zIcp\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `content_season`
--

CREATE TABLE `content_season` (
  `season_id` int NOT NULL,
  `content_id` int NOT NULL,
  `season_number` int NOT NULL,
  `episode_count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `director`
--

CREATE TABLE `director` (
  `director_id` int NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `director`
--

INSERT INTO `director` (`director_id`, `nickname`, `first_name`, `last_name`) VALUES
(1, 'Martin Scorsese', 'Martin', 'Charles Scorsese'),
(2, 'David Fincher', 'David', 'Fincher'),
(4, 'Steven Spielberg', 'Steven', 'Spielberg'),
(5, 'Quentin Tarantino', 'Quentin', 'Jerome Tarantino'),
(6, 'Christopher Nolan', 'Christopher', 'Edward Nolan CBE'),
(7, 'Thomas Anderson', 'Paul', 'Thomas Anderson'),
(8, 'Wes Anderson', 'Wesley', 'Wales Anderson'),
(9, 'Stanley Kubrick', 'Stanley', 'Kubrick'),
(10, 'Billy Wilder', 'Billy', 'Wilder'),
(11, 'Francis Coppola', 'Francis', 'Ford Coppola'),
(12, 'Pedro Almodovar', 'Pedro', 'Almodovar'),
(13, 'Sofia Coppola', 'Sofia', 'Coppola');

-- --------------------------------------------------------

--
-- Table structure for table `episode`
--

CREATE TABLE `episode` (
  `episode_id` int NOT NULL,
  `season_id` int NOT NULL,
  `episode_number` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre_id` int NOT NULL,
  `genre_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_id`, `genre_name`) VALUES
(1, 'action'),
(2, 'adventure'),
(4, 'animation'),
(5, 'biography'),
(6, 'comedy'),
(7, 'crime');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `recommendation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content_id` int NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`recommendation_id`, `user_id`, `content_id`, `score`, `created_at`) VALUES
(1, 8, 4, '7.00', '2024-02-12 03:42:01'),
(6, 8, 2, '6.70', '2024-02-12 08:22:32'),
(7, 8, 8, '8.00', '2024-02-12 08:22:53'),
(8, 8, 11, '9.00', '2024-02-12 08:23:07'),
(9, 8, 10, '9.50', '2024-02-12 08:23:23'),
(10, 8, 3, '8.50', '2024-02-12 08:23:44'),
(11, 8, 1, '7.80', '2024-02-12 08:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content_id` int NOT NULL,
  `rating` decimal(10,2) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `content_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 8, 10, '10.00', 'I think the show is very complex but not boring', '2024-02-12 08:34:42'),
(2, 8, 11, '10.00', 'Very good movie to understand the complexity in Breaking Bad worlds', '2024-02-12 08:35:59'),
(3, 8, 12, '9.00', 'Good', '2024-02-14 14:37:50'),
(5, 8, 14, '7.00', 'just Nice\r\n', '2024-02-14 14:39:14'),
(6, 8, 13, '9.75', 'Funny guy', '2024-02-14 14:41:52'),
(7, 282, 1, '6.70', 'Confused\r\n', '2024-02-14 14:43:27'),
(8, 282, 10, '8.70', 'Complex\r\n', '2024-02-14 14:43:50'),
(9, 282, 14, '7.30', 'Musical\r\n', '2024-02-14 14:44:12'),
(10, 20, 14, '6.60', 'Boring', '2024-02-14 14:44:30'),
(11, 282, 16, '9.67', 'Good film', '2024-02-15 03:38:36'),
(12, 8, 19, '10.00', 'It\'s all about racing', '2024-02-15 03:39:22'),
(13, 282, 19, '7.80', 'No opinion, Just good.', '2024-02-15 03:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `studio`
--

CREATE TABLE `studio` (
  `studio_id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `studio`
--

INSERT INTO `studio` (`studio_id`, `name`) VALUES
(1, 'Studio Ghibli, Inc'),
(2, 'Warner Bros'),
(3, '20th Century Fox'),
(4, 'Lionsgate'),
(5, 'Walt Disney Pictures'),
(6, 'Sony'),
(7, 'Columbia Pictures'),
(9, 'Universal Pictures');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan`
--

CREATE TABLE `subscription_plan` (
  `subscription_plan_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `concurrent_streams` int NOT NULL,
  `content_resolution` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription_plan`
--

INSERT INTO `subscription_plan` (`subscription_plan_id`, `name`, `price`, `concurrent_streams`, `content_resolution`, `description`) VALUES
(1, 'Basic Plan', '9.99', 1, 'HD', 'Access to basic features'),
(2, 'Standard Plan', '14.99', 2, 'Full HD', 'More features for a standard experience'),
(3, 'Premium Plan', '19.99', 4, '4K', 'Premium features and 4K streaming'),
(4, 'Luxury Plan', '49.99', 20, 'Full HD', 'Luxuries in all that matters\r\nhehehe ...');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `subscription_plan_id` int DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `active` enum('0','1') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `subscription_plan_id`, `username`, `email`, `password`, `avatar`, `role`, `active`, `created_at`, `last_login`) VALUES
(8, 3, 'krazy-8', 'krazier.eights@gmail.com', '$2y$10$ulsogjm/68OVG9L4VIFkhenawFsfwCKny2mlRkjjr6Rw/JBCzI85G', '/assets/img/avatar/f63d2ba2b454bf5d3bcc88545a734cf2.png', '1', '1', '2023-12-25 16:21:59', '2024-02-14 20:19:30'),
(20, 1, 'user7901', 'user30689@example.com', 'password', NULL, '0', '0', '2023-03-13 16:22:36', '2023-05-22 16:22:36'),
(21, 1, 'user15086', 'user31732@example.com', 'password', NULL, '0', '0', '2023-09-08 16:22:36', '2023-12-07 16:22:36'),
(22, 1, 'user23610', 'user6833@example.com', 'password', NULL, '0', '0', '2023-03-10 16:22:36', '2023-03-15 16:22:36'),
(23, 2, 'user12499', 'user17596@example.com', 'password', NULL, '0', '0', '2023-07-29 16:22:36', '2023-02-06 16:22:36'),
(24, 3, 'user13380', 'user19357@example.com', 'password', NULL, '0', '0', '2023-05-22 16:22:36', '2023-05-02 16:22:36'),
(25, 3, 'user18239', 'user8065@example.com', 'password', NULL, '0', '0', '2023-07-09 16:22:36', '2023-12-21 16:22:36'),
(26, 3, 'user23760', 'user785@example.com', 'password', NULL, '0', '0', '2023-02-09 16:22:36', '2023-04-06 16:22:36'),
(27, 2, 'user21402', 'user21889@example.com', 'password', NULL, '0', '0', '2023-09-24 16:22:36', '2023-04-10 16:22:36'),
(28, 2, 'user13059', 'user13397@example.com', 'password', NULL, '0', '0', '2023-04-03 16:22:36', '2023-02-14 16:22:36'),
(29, 1, 'user25662', 'user9155@example.com', 'password', NULL, '0', '0', '2024-01-09 16:22:36', '2023-08-05 16:22:36'),
(30, 1, 'user18548', 'user6911@example.com', 'password', NULL, '0', '0', '2023-09-20 16:22:36', '2023-11-15 16:22:36'),
(31, 2, 'user16044', 'user27705@example.com', 'password', NULL, '0', '0', '2023-05-18 16:22:36', '2024-01-08 16:22:36'),
(32, 3, 'user10641', 'user585@example.com', 'password', NULL, '0', '0', '2023-12-16 16:22:36', '2023-06-28 16:22:36'),
(33, 1, 'user25671', 'user9005@example.com', 'password', NULL, '0', '0', '2024-01-18 16:22:36', '2023-09-12 16:22:36'),
(34, 2, 'user11437', 'user4247@example.com', 'password', NULL, '0', '0', '2023-06-25 16:22:36', '2023-06-07 16:22:36'),
(35, 1, 'user9630', 'user26532@example.com', 'password', NULL, '0', '0', '2023-12-22 16:22:36', '2023-11-28 16:22:36'),
(36, 3, 'user16302', 'user26542@example.com', 'password', NULL, '0', '0', '2023-07-29 16:22:36', '2023-11-27 16:22:36'),
(37, 2, 'user7543', 'user15878@example.com', 'password', NULL, '0', '0', '2023-05-21 16:22:36', '2024-01-01 16:22:36'),
(38, 2, 'user25847', 'user7011@example.com', 'password', NULL, '0', '0', '2023-05-13 16:22:36', '2023-02-03 16:22:36'),
(39, 1, 'user11707', 'user2639@example.com', 'password', NULL, '0', '0', '2023-09-30 16:22:36', '2023-08-13 16:22:36'),
(40, 1, 'user21356', 'user17522@example.com', 'password', NULL, '0', '0', '2023-05-20 16:22:36', '2023-02-18 16:22:36'),
(41, 3, 'user9086', 'user22656@example.com', 'password', NULL, '0', '0', '2023-07-05 16:22:36', '2023-03-23 16:22:36'),
(42, 2, 'user21279', 'user14556@example.com', 'password', NULL, '0', '0', '2023-10-27 16:22:36', '2024-01-21 16:22:36'),
(43, 1, 'user6994', 'user9470@example.com', 'password', NULL, '0', '0', '2023-04-19 16:22:36', '2023-12-26 16:22:36'),
(44, 2, 'user26296', 'user5305@example.com', 'password', NULL, '0', '0', '2023-08-29 16:22:36', '2023-06-03 16:22:36'),
(45, 1, 'user5414', 'user795@example.com', 'password', NULL, '0', '0', '2023-06-16 16:22:36', '2023-12-31 16:22:36'),
(46, 2, 'user23606', 'user24208@example.com', 'password', NULL, '0', '0', '2023-08-01 16:22:36', '2023-09-25 16:22:36'),
(47, 1, 'user23153', 'user21779@example.com', 'password', NULL, '0', '0', '2023-11-27 16:22:36', '2023-02-18 16:22:36'),
(48, 3, 'user30577', 'user24889@example.com', 'password', NULL, '0', '0', '2023-02-08 16:22:36', '2023-06-03 16:22:36'),
(49, 1, 'user8417', 'user13453@example.com', 'password', NULL, '0', '0', '2023-10-29 16:22:36', '2023-12-30 16:22:36'),
(50, 1, 'user21264', 'user9489@example.com', 'password', NULL, '0', '0', '2023-07-30 16:22:36', '2023-05-24 16:22:36'),
(51, 3, 'user1982', 'user12979@example.com', 'password', NULL, '0', '0', '2023-04-27 16:22:36', '2023-05-31 16:22:36'),
(52, 1, 'user14096', 'user6595@example.com', 'password', NULL, '0', '0', '2023-05-14 16:22:36', '2024-01-29 16:22:36'),
(53, 2, 'user16581', 'user18005@example.com', 'password', NULL, '0', '0', '2023-11-17 16:22:36', '2023-08-31 16:22:36'),
(54, 1, 'user11164', 'user24003@example.com', 'password', NULL, '0', '0', '2023-06-29 16:22:36', '2023-04-09 16:22:36'),
(55, 3, 'user30021', 'user18036@example.com', 'password', NULL, '0', '0', '2024-01-31 16:22:36', '2023-09-11 16:22:36'),
(56, 1, 'user24308', 'user22353@example.com', 'password', NULL, '0', '0', '2023-12-03 16:22:36', '2023-04-10 16:22:36'),
(57, 1, 'user22787', 'user14355@example.com', 'password', NULL, '0', '0', '2023-12-26 16:22:36', '2023-11-13 16:22:36'),
(58, 2, 'user19020', 'user28720@example.com', 'password', NULL, '0', '0', '2023-06-29 16:22:36', '2023-09-10 16:22:36'),
(59, 2, 'user9339', 'user13504@example.com', 'password', NULL, '0', '0', '2023-11-26 16:22:36', '2023-05-18 16:22:36'),
(60, 3, 'user9365', 'user12806@example.com', 'password', NULL, '0', '0', '2024-01-04 16:22:36', '2023-11-07 16:22:36'),
(61, 2, 'user1684', 'user7652@example.com', 'password', NULL, '0', '0', '2023-02-03 16:22:36', '2023-10-18 16:22:36'),
(62, 3, 'user25793', 'user27513@example.com', 'password', NULL, '0', '0', '2023-04-13 16:22:36', '2023-07-15 16:22:36'),
(63, 1, 'user22210', 'user9178@example.com', 'password', NULL, '0', '0', '2023-09-17 16:22:36', '2024-01-10 16:22:36'),
(64, 3, 'user32968', 'user28523@example.com', 'password', NULL, '0', '0', '2023-10-11 16:22:36', '2023-02-05 16:22:36'),
(65, 2, 'user9432', 'user10610@example.com', 'password', NULL, '0', '0', '2023-05-06 16:22:36', '2023-05-01 16:22:36'),
(66, 1, 'user2713', 'user8328@example.com', 'password', NULL, '0', '0', '2024-01-31 16:22:36', '2023-10-24 16:22:36'),
(67, 2, 'user28458', 'user3584@example.com', 'password', NULL, '0', '0', '2023-02-10 16:22:36', '2023-07-12 16:22:36'),
(68, 2, 'user32222', 'user21714@example.com', 'password', NULL, '0', '0', '2023-09-24 16:22:36', '2023-04-04 16:22:36'),
(69, 2, 'user5759', 'user22565@example.com', 'password', NULL, '0', '0', '2023-03-22 16:22:36', '2023-10-14 16:22:36'),
(70, 1, 'user1222', 'user32716@example.com', 'password', NULL, '0', '0', '2023-04-16 16:22:36', '2024-01-17 16:22:36'),
(71, 3, 'user7278', 'user29307@example.com', 'password', NULL, '0', '0', '2023-05-07 16:22:36', '2024-01-08 16:22:36'),
(72, 2, 'user7372', 'user28134@example.com', 'password', NULL, '0', '0', '2023-07-13 16:22:36', '2023-11-02 16:22:36'),
(73, 3, 'user28975', 'user970@example.com', 'password', NULL, '0', '0', '2023-07-20 16:22:36', '2023-06-27 16:22:36'),
(74, 3, 'user23954', 'user8832@example.com', 'password', NULL, '0', '0', '2023-12-02 16:22:36', '2024-01-14 16:22:36'),
(75, 3, 'user17703', 'user9990@example.com', 'password', NULL, '0', '0', '2023-03-08 16:22:36', '2023-06-18 16:22:36'),
(76, 1, 'user7742', 'user26235@example.com', 'password', NULL, '0', '0', '2023-11-06 16:22:36', '2023-04-04 16:22:36'),
(77, 1, 'user10787', 'user8085@example.com', 'password', NULL, '0', '0', '2023-11-05 16:22:36', '2023-08-10 16:22:36'),
(78, 2, 'user4498', 'user9520@example.com', 'password', NULL, '0', '0', '2024-01-24 16:22:36', '2023-10-30 16:22:36'),
(79, 1, 'user7940', 'user25887@example.com', 'password', NULL, '0', '0', '2023-12-02 16:22:36', '2023-07-29 16:22:36'),
(80, 1, 'user19929', 'user19109@example.com', 'password', NULL, '0', '0', '2024-01-06 16:22:36', '2023-06-11 16:22:36'),
(81, 1, 'user16358', 'user1097@example.com', 'password', NULL, '0', '0', '2023-05-25 16:22:36', '2023-09-22 16:22:36'),
(82, 1, 'user5257', 'user11143@example.com', 'password', NULL, '0', '0', '2023-11-21 16:22:36', '2023-02-06 16:22:36'),
(83, 1, 'user8485', 'user24824@example.com', 'password', NULL, '0', '0', '2023-02-16 16:22:36', '2023-07-10 16:22:36'),
(84, 2, 'user31325', 'user3266@example.com', 'password', NULL, '0', '0', '2023-06-02 16:22:36', '2024-01-11 16:22:36'),
(85, 1, 'user27511', 'user16549@example.com', 'password', NULL, '0', '0', '2024-01-30 16:22:36', '2023-07-18 16:22:36'),
(86, 3, 'user15563', 'user23169@example.com', 'password', NULL, '0', '0', '2024-01-05 16:22:36', '2023-10-19 16:22:36'),
(87, 2, 'user3240', 'user28196@example.com', 'password', NULL, '0', '0', '2023-02-24 16:22:36', '2023-12-08 16:22:36'),
(88, 1, 'user22726', 'user25159@example.com', 'password', NULL, '0', '0', '2023-05-12 16:22:36', '2023-09-17 16:22:36'),
(89, 3, 'user7457', 'user15448@example.com', 'password', NULL, '0', '0', '2023-06-11 16:22:36', '2023-04-01 16:22:36'),
(90, 3, 'user6059', 'user7432@example.com', 'password', NULL, '0', '0', '2023-07-09 16:22:36', '2023-11-28 16:22:36'),
(91, 1, 'user18148', 'user1017@example.com', 'password', NULL, '0', '0', '2023-07-27 16:22:36', '2023-08-01 16:22:36'),
(92, 3, 'user12670', 'user6442@example.com', 'password', NULL, '0', '0', '2023-04-06 16:22:36', '2023-07-16 16:22:36'),
(93, 3, 'user14700', 'user15810@example.com', 'password', NULL, '0', '0', '2024-01-15 16:22:36', '2023-04-08 16:22:36'),
(94, 2, 'user10123', 'user26019@example.com', 'password', NULL, '0', '0', '2023-02-04 16:22:36', '2023-06-21 16:22:36'),
(95, 1, 'user7659', 'user13153@example.com', 'password', NULL, '0', '0', '2023-10-21 16:22:36', '2023-11-08 16:22:36'),
(96, 1, 'user5827', 'user3909@example.com', 'password', NULL, '0', '0', '2024-01-10 16:22:36', '2023-02-17 16:22:36'),
(97, 1, 'user27353', 'user11244@example.com', 'password', NULL, '0', '0', '2023-11-11 16:22:36', '2023-12-23 16:22:36'),
(98, 2, 'user6573', 'user6396@example.com', 'password', NULL, '0', '0', '2023-09-20 16:22:36', '2023-10-28 16:22:36'),
(99, 3, 'user4801', 'user30320@example.com', 'password', NULL, '0', '0', '2023-12-21 16:22:36', '2023-03-28 16:22:36'),
(100, 2, 'user17755', 'user26899@example.com', 'password', NULL, '0', '0', '2023-08-26 16:22:36', '2023-04-29 16:22:36'),
(101, 3, 'user4512', 'user27181@example.com', 'password', NULL, '0', '0', '2023-06-02 16:22:36', '2023-03-07 16:22:36'),
(102, 2, 'user9940', 'user20173@example.com', 'password', NULL, '0', '0', '2023-12-16 16:22:36', '2023-03-31 16:22:36'),
(103, 3, 'user13351', 'user1487@example.com', 'password', NULL, '0', '0', '2024-01-25 16:22:36', '2023-02-11 16:22:36'),
(104, 2, 'user625', 'user3659@example.com', 'password', NULL, '0', '0', '2023-08-06 16:22:36', '2023-12-15 16:22:36'),
(105, 1, 'user12169', 'user27894@example.com', 'password', NULL, '0', '0', '2023-12-31 16:22:36', '2023-02-26 16:22:36'),
(106, 2, 'user3722', 'user17765@example.com', 'password', NULL, '0', '0', '2023-10-04 16:22:36', '2024-01-14 16:22:36'),
(107, 3, 'user3229', 'user14436@example.com', 'password', NULL, '0', '0', '2023-03-19 16:22:36', '2024-01-05 16:22:36'),
(108, 3, 'user32454', 'user27219@example.com', 'password', NULL, '0', '0', '2023-12-04 16:22:36', '2023-09-23 16:22:36'),
(109, 3, 'user28041', 'user4159@example.com', 'password', NULL, '0', '0', '2023-12-27 16:22:36', '2023-12-17 16:22:36'),
(110, 3, 'user12956', 'user27662@example.com', 'password', NULL, '0', '0', '2023-02-08 16:22:36', '2023-08-30 16:22:36'),
(111, 3, 'user32387', 'user22732@example.com', 'password', NULL, '0', '0', '2023-08-05 16:22:36', '2023-08-29 16:22:36'),
(112, 3, 'user19949', 'user26006@example.com', 'password', NULL, '0', '0', '2023-12-25 16:22:36', '2023-11-25 16:22:36'),
(113, 2, 'user18663', 'user19136@example.com', 'password', NULL, '0', '0', '2023-11-24 16:22:36', '2023-11-09 16:22:36'),
(114, 2, 'user31437', 'user15912@example.com', 'password', NULL, '0', '0', '2023-07-13 16:22:36', '2023-09-25 16:22:36'),
(115, 2, 'user17509', 'user12545@example.com', 'password', NULL, '0', '0', '2023-10-13 16:22:36', '2023-09-08 16:22:36'),
(116, 3, 'user8695', 'user1443@example.com', 'password', NULL, '0', '0', '2023-08-27 16:22:36', '2024-01-18 16:22:36'),
(117, 1, 'user23420', 'user7365@example.com', 'password', NULL, '0', '0', '2023-02-03 16:22:36', '2023-10-07 16:22:36'),
(118, 2, 'user31165', 'user11770@example.com', 'password', NULL, '0', '0', '2023-02-16 16:22:36', '2023-05-06 16:22:36'),
(119, 2, 'user7202', 'user24966@example.com', 'password', NULL, '0', '0', '2023-12-28 16:22:36', '2023-11-07 16:22:36'),
(120, 2, 'user6523', 'user21564@example.com', 'password', NULL, '0', '0', '2023-06-10 16:22:36', '2023-10-16 16:22:36'),
(121, 3, 'user10170', 'user4881@example.com', 'password', NULL, '0', '0', '2023-04-09 16:22:36', '2023-06-11 16:22:36'),
(122, 1, 'user959', 'user24467@example.com', 'password', NULL, '0', '0', '2023-07-03 16:22:36', '2023-05-16 16:22:36'),
(123, 2, 'user3259', 'user2589@example.com', 'password', NULL, '0', '0', '2023-12-29 16:22:36', '2023-11-05 16:22:36'),
(124, 3, 'user22356', 'user30707@example.com', 'password', NULL, '0', '0', '2023-06-30 16:22:36', '2023-11-18 16:22:36'),
(125, 1, 'user18059', 'user8971@example.com', 'password', NULL, '0', '0', '2023-05-15 16:22:36', '2023-04-18 16:22:36'),
(126, 3, 'user131', 'user18609@example.com', 'password', NULL, '0', '0', '2023-04-23 16:22:36', '2023-11-12 16:22:36'),
(127, 3, 'user32746', 'user14842@example.com', 'password', NULL, '0', '0', '2023-10-23 16:22:36', '2024-01-11 16:22:36'),
(128, 3, 'user22751', 'user31517@example.com', 'password', NULL, '0', '0', '2023-05-29 16:22:36', '2023-07-11 16:22:36'),
(129, 3, 'user4926', 'user8451@example.com', 'password', NULL, '0', '0', '2023-04-07 16:22:36', '2023-09-23 16:22:36'),
(130, 1, 'user11034', 'user5500@example.com', 'password', NULL, '0', '0', '2023-04-04 16:22:36', '2023-06-04 16:22:36'),
(131, 3, 'user422', 'user18851@example.com', 'password', NULL, '0', '0', '2023-04-19 16:22:36', '2023-11-02 16:22:36'),
(132, 3, 'user183', 'user17322@example.com', 'password', NULL, '0', '0', '2023-07-04 16:22:36', '2023-09-27 16:22:36'),
(133, 1, 'user31684', 'user8131@example.com', 'password', NULL, '0', '0', '2023-09-20 16:22:36', '2023-12-24 16:22:36'),
(134, 1, 'user22001', 'user23244@example.com', 'password', NULL, '0', '0', '2023-08-01 16:22:36', '2023-08-25 16:22:36'),
(135, 2, 'user715', 'user16203@example.com', 'password', NULL, '0', '0', '2023-09-21 16:22:36', '2023-09-19 16:22:36'),
(136, 3, 'user32249', 'user7158@example.com', 'password', NULL, '0', '0', '2023-12-01 16:22:36', '2023-11-16 16:22:36'),
(137, 1, 'user1020', 'user17113@example.com', 'password', NULL, '0', '0', '2023-08-12 16:22:36', '2023-04-02 16:22:36'),
(138, 3, 'user13256', 'user11100@example.com', 'password', NULL, '0', '0', '2023-08-13 16:22:36', '2023-09-23 16:22:36'),
(139, 3, 'user3202', 'user27357@example.com', 'password', NULL, '0', '0', '2023-04-10 16:22:36', '2023-06-22 16:22:36'),
(140, 2, 'user23156', 'user26779@example.com', 'password', NULL, '0', '0', '2023-02-26 16:22:36', '2023-11-01 16:22:36'),
(141, 1, 'user25314', 'user3840@example.com', 'password', NULL, '0', '0', '2023-10-16 16:22:36', '2023-12-11 16:22:36'),
(142, 2, 'user11942', 'user3520@example.com', 'password', NULL, '0', '0', '2023-08-20 16:22:36', '2023-02-20 16:22:36'),
(143, 2, 'user32685', 'user6098@example.com', 'password', NULL, '0', '0', '2023-02-11 16:22:36', '2023-10-09 16:22:36'),
(144, 1, 'user21223', 'user15293@example.com', 'password', NULL, '0', '0', '2023-09-14 16:22:36', '2023-07-18 16:22:36'),
(145, 1, 'user30198', 'user24998@example.com', 'password', NULL, '0', '0', '2024-01-21 16:22:36', '2023-03-07 16:22:36'),
(146, 2, 'user13898', 'user9410@example.com', 'password', NULL, '0', '0', '2023-12-05 16:22:36', '2023-02-17 16:22:36'),
(147, 3, 'user26998', 'user8222@example.com', 'password', NULL, '0', '0', '2023-04-14 16:22:36', '2023-10-23 16:22:36'),
(148, 3, 'user20821', 'user9972@example.com', 'password', NULL, '0', '0', '2023-06-19 16:22:36', '2023-11-16 16:22:36'),
(149, 1, 'user9201', 'user18029@example.com', 'password', NULL, '0', '0', '2023-03-19 16:22:36', '2023-05-01 16:22:36'),
(150, 3, 'user1464', 'user12507@example.com', 'password', NULL, '0', '0', '2023-05-06 16:22:36', '2023-06-29 16:22:36'),
(151, 2, 'user9997', 'user21492@example.com', 'password', NULL, '0', '0', '2023-10-06 16:22:36', '2023-05-27 16:22:36'),
(152, 2, 'user2040', 'user14708@example.com', 'password', NULL, '0', '0', '2024-01-24 16:22:36', '2023-04-19 16:22:36'),
(153, 2, 'user20802', 'user7864@example.com', 'password', NULL, '0', '0', '2023-10-12 16:22:36', '2023-04-05 16:22:36'),
(154, 3, 'user10346', 'user21924@example.com', 'password', NULL, '0', '0', '2023-09-24 16:22:36', '2023-04-10 16:22:36'),
(155, 3, 'user7095', 'user5007@example.com', 'password', NULL, '0', '0', '2023-12-22 16:22:36', '2023-12-23 16:22:36'),
(282, 4, 'krazy-69', 'krazier.sixtynine@gmail.com', '$2y$10$nR5ovPrI7stP9kiOK8zOF.hTu4F/4TdLWFH2/aDbio5DtuU//bP06', '/assets/img/avatar/4df18b63ccda2d05bf39dbc645db3b85.png', '0', '1', '2024-02-14 07:42:58', '2024-02-14 21:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_watch_history`
--

CREATE TABLE `user_watch_history` (
  `watch_history_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content_id` int NOT NULL,
  `episode_id` int NOT NULL,
  `last_watched_position` int NOT NULL,
  `watch_completed` enum('0','1') NOT NULL,
  `watched_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actor`
--
ALTER TABLE `actor`
  ADD PRIMARY KEY (`actor_id`),
  ADD UNIQUE KEY `Nickname` (`nickname`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `content_season`
--
ALTER TABLE `content_season`
  ADD PRIMARY KEY (`season_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`director_id`),
  ADD UNIQUE KEY `Nickname` (`nickname`);

--
-- Indexes for table `episode`
--
ALTER TABLE `episode`
  ADD PRIMARY KEY (`episode_id`),
  ADD KEY `season_id` (`season_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`recommendation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `studio`
--
ALTER TABLE `studio`
  ADD PRIMARY KEY (`studio_id`);

--
-- Indexes for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  ADD PRIMARY KEY (`subscription_plan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `subscription_plan_id` (`subscription_plan_id`);

--
-- Indexes for table `user_watch_history`
--
ALTER TABLE `user_watch_history`
  ADD PRIMARY KEY (`watch_history_id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `episode_id` (`episode_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actor`
--
ALTER TABLE `actor`
  MODIFY `actor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `content_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `content_season`
--
ALTER TABLE `content_season`
  MODIFY `season_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `director`
--
ALTER TABLE `director`
  MODIFY `director_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `episode`
--
ALTER TABLE `episode`
  MODIFY `episode_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `genre_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `recommendation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `studio`
--
ALTER TABLE `studio`
  MODIFY `studio_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  MODIFY `subscription_plan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `user_watch_history`
--
ALTER TABLE `user_watch_history`
  MODIFY `watch_history_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content_season`
--
ALTER TABLE `content_season`
  ADD CONSTRAINT `content_season_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `episode`
--
ALTER TABLE `episode`
  ADD CONSTRAINT `episode_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `content_season` (`season_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plan` (`subscription_plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_watch_history`
--
ALTER TABLE `user_watch_history`
  ADD CONSTRAINT `user_watch_history_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_watch_history_ibfk_2` FOREIGN KEY (`episode_id`) REFERENCES `episode` (`episode_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_watch_history_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
