<?php


class Config implements Injectable {
	private int $casinoPagination = 10;
	private int $gamePagination = 6;
	private int $postPagination = 10;


	public function GetCasinoPagination(): int {
		return $this->casinoPagination;
	}

	public function GetGamePagination(): int {
		return $this->gamePagination;
	}

	public function GetPostPagination(): int {
		return $this->postPagination;
	}
}