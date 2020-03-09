ALTER TABLE `quotation` CHANGE `status` `status` SET('confirmed','canceled','paid','wait','after_bargain') NOT NULL DEFAULT 'wait';
