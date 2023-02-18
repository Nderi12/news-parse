<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * interface SourceUrlInterface 
 * Specifies methods for getting (CSS) selectors 
 * for different parts of a web page URL code 
 */
interface SourceUrlInterface { 
  /**
   * method getUrl() 
   * Should return the URL of the page we are going to crawl and parse
   */
  public function getUrl(): string;

  public function getWrapperSelector(): string;

  public function getTitleSelector(): string;

  public function getDescriptionSelector(): string;

  public function getImageSelector(): string;
}
