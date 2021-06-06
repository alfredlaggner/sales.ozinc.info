<?php

	namespace App\Exports;

	use App\InvoiceNote;
	use Illuminate\Contracts\View\View;
	use Maatwebsite\Excel\Concerns\FromView;
	class DreamersExport implements FromView
	{
		private $data;

		public function __construct($data)
		{
			$this->data = $data;
		}
		public function view(): View
		{
			return view('exports.dreamers', ['sales_lines' => $this->data]);
		}
	}
