SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `Contributor` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_login` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_password` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_label` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_mail` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_token` varchar(256) COLLATE utf8mb4_unicode_ci,
  `c_role` varchar(256) COLLATE utf8mb4_unicode_ci,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `Form` (
  `f_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `f_version` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `f_author` int(11) NOT NULL,
  `f_validator` int(11) NOT NULL,
  `f_description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `FormAttempt` (
  `fa_id` int(11) NOT NULL AUTO_INCREMENT,
  `fa_form_id` int(11) NOT NULL,
  `fo_respondant` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `FormAttemptElement` (
  `fae_id` int(11) NOT NULL AUTO_INCREMENT,
  `fae_content` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fae_fec_id` int(11) NOT NULL,
  `fae_fa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `FormElement` (
  `fe_id` int(11) NOT NULL AUTO_INCREMENT,
  `fe_title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fe_type` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fe_order` int(11) NOT NULL,
  `fe_form_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `FormElementContent` (
  `fec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fec_html_id` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fec_html_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fec_html_value` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fec_html_label` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fec_receivers` text COLLATE utf8mb4_unicode_ci,
  `fec_parent_id` int(11) NOT NULL,
  `fec_referent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `Contributor`
  ADD PRIMARY KEY (`c_id`);


ALTER TABLE `Form`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `fk_form_contributor_id` (`f_author`),
  ADD KEY `fk_form_validator_id` (`f_validator`);


ALTER TABLE `FormAttempt`
  ADD PRIMARY KEY (`fa_id`),
  ADD KEY `fk_formattempt_form_id` (`fa_form_id`);


ALTER TABLE `FormAttemptElement`
  ADD PRIMARY KEY (`fae_id`),
  ADD KEY `fk_formattemptelement_formelementcontent_id` (`fae_fec_id`),
  ADD KEY `fk_formattempt_element_formattempt_id` (`fae_fa_id`);

ALTER TABLE `FormElement`
  ADD PRIMARY KEY (`fe_id`),
  ADD KEY `fk_formelement_form_id` (`fe_form_id`);


ALTER TABLE `FormElementContent`
  ADD PRIMARY KEY (`fec_id`),
  ADD KEY `fk_formelementcontent_referent_id` (`fec_referent_id`),
  ADD KEY `fk_formelementcontent_parent_id` (`fec_parent_id`);


ALTER TABLE `Form`
  ADD CONSTRAINT `fk_form_contributor_id` FOREIGN KEY (`f_author`) REFERENCES `Contributor` (`c_id`),
  ADD CONSTRAINT `fk_form_validator_id` FOREIGN KEY (`f_validator`) REFERENCES `Contributor` (`c_id`);


ALTER TABLE `FormAttempt`
  ADD CONSTRAINT `fk_formattempt_form_id` FOREIGN KEY (`fa_form_id`) REFERENCES `Form` (`f_id`);


ALTER TABLE `FormAttemptElement`
  ADD CONSTRAINT `fk_formattempt_element_formattempt_id` FOREIGN KEY (`fae_fa_id`) REFERENCES `FormAttempt` (`fa_id`),
  ADD CONSTRAINT `fk_formattemptelement_formelementcontent_id` FOREIGN KEY (`fae_fec_id`) REFERENCES `FormElementContent` (`fec_id`);


ALTER TABLE `FormElement`
  ADD CONSTRAINT `fk_formelement_form_id` FOREIGN KEY (`fe_form_id`) REFERENCES `Form` (`f_id`);


ALTER TABLE `FormElementContent`
  ADD CONSTRAINT `fk_formelementcontent_parent_id` FOREIGN KEY (`fec_parent_id`) REFERENCES `FormElement` (`fe_id`),
  ADD CONSTRAINT `fk_formelementcontent_referent_id` FOREIGN KEY (`fec_referent_id`) REFERENCES `FormElementContent` (`fec_id`);
COMMIT;