-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: localhost
-- Létrehozás ideje: 2024. Jún 25. 22:25
-- Szerver verzió: 5.0.45
-- PHP Verzió: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Tábla szerkezet: `arak`
-- 

CREATE TABLE `arak` (
  `id_ar` int(7) NOT NULL auto_increment,
  `kozmu` int(7) NOT NULL,
  `ev` int(4) NOT NULL,
  `ho` int(2) NOT NULL,
  `egysegar` int(7) NOT NULL,
  PRIMARY KEY  (`id_ar`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Tábla adatok: `arak`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `ceg`
-- 

CREATE TABLE `ceg` (
  `id_ceg` int(7) NOT NULL auto_increment,
  `cegnev` varchar(256) NOT NULL,
  `telephely` int(7) NOT NULL,
  `sorrend` int(7) NOT NULL,
  `aktiv` enum('igen','nem') NOT NULL,
  `nyito_ev` int(4) NOT NULL,
  `nyito_ho` int(2) NOT NULL,
  `zaro_ev` int(4) NOT NULL,
  `zaro_ho` int(2) NOT NULL,
  PRIMARY KEY  (`id_ceg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Tábla adatok: `ceg`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `fogyasztas`
-- 

CREATE TABLE `fogyasztas` (
  `id_fogy` int(7) NOT NULL auto_increment,
  `id_mero` int(7) NOT NULL,
  `ev` int(4) NOT NULL,
  `ho` int(2) NOT NULL,
  `nyito` int(7) NOT NULL,
  `zaro` int(7) NOT NULL,
  PRIMARY KEY  (`id_fogy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Tábla adatok: `fogyasztas`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `kozmu`
-- 

CREATE TABLE `kozmu` (
  `id_kozmu` int(7) NOT NULL auto_increment,
  `kozmu` varchar(512) NOT NULL,
  `mertekegyseg` varchar(32) NOT NULL,
  `sorrend` int(7) NOT NULL,
  PRIMARY KEY  (`id_kozmu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin2;

-- 
-- Tábla adatok: `kozmu`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `mero`
-- 

CREATE TABLE `mero` (
  `id_mero` int(7) NOT NULL auto_increment,
  `id_ceg` int(7) NOT NULL,
  `meroazon` varchar(128) NOT NULL,
  `kozmu` int(7) NOT NULL,
  `nyito_ev` int(4) NOT NULL,
  `nyito_ho` int(2) NOT NULL,
  `nyito_allas` int(7) NOT NULL,
  `zaro_ev` int(4) NOT NULL,
  `zaro_ho` int(2) NOT NULL,
  `zaro_allas` int(7) NOT NULL,
  `aktiv` enum('igen','nem') NOT NULL,
  `sorrend` int(7) NOT NULL,
  PRIMARY KEY  (`id_mero`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Tábla adatok: `mero`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `telephely`
-- 

CREATE TABLE `telephely` (
  `id_telephely` int(7) NOT NULL auto_increment,
  `cim` varchar(512) NOT NULL,
  `sorrend` int(7) NOT NULL,
  PRIMARY KEY  (`id_telephely`)
) ENGINE=MyISAM DEFAULT CHARSET=latin2;

-- 
-- Tábla adatok: `telephely`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `user`
-- 

CREATE TABLE `user` (
  `id_user` int(7) NOT NULL auto_increment,
  `user` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY  (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin2;

-- 
-- Tábla adatok: `user`
-- 

INSERT INTO `user` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3');
