<?php
/**
 * @link http://pagination.ru/
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 */
class Krugozor_Pagination_Manager
{
    /**
     * ������������ ���������� ������� �� ����,
     * ������� ���������� �������� �� ����� ��������.
     * ���� �� ���������� ������������.
     *
     * @var int
     */
    private $limit;

    /**
     * ���������� ������ �� ��������, �����������
     * ����� ��������-������� ���������� << � >>.
     * ����������, ��� ���������� ������ � ���������
     * ��������� � ��������� �����.
     *
     * @var int
     */
    private $link_count;

    /**
     * ����� ������� ��������.
     *
     * @var int
     */
    private $current_page;

    /**
     * ����� �������� ����������.
     *
     * @var int
     */
    private $current_sep;

    /**
     * ��������� �������� ��� SQL-��������� LIMIT.
     *
     * @var int
     */
    private $start_limit;

    /**
     * �������� �������� ��� SQL-��������� LIMIT.
     *
     * @var int
     */
    private $stop_limit;

    /**
     * ����� ���������� ������� � ������� ��, �����������
     * � ����������� � ������������ ������ ��� ����������.
     *
     * @var int
     */
    private $total_rows;

    /**
     * ���������� ������� ����������, ������� ���������, ���� �� ���� ��������
     * ���������� �������� $this->limit ������� �� ����.
     *
     * @var int
     */
    private $total_pages;

    /**
     * ���������� ��������� ������, �� ������� ����� ��������� ��.
     *
     * @var int
     */
    private $total_blocks;

    /**
     * ��� ���������� �� Request, �������� ������� ����� ��������� ��������.
     *
     * @var int
     */
    private $page_var_name;

    /**
     * ��� ���������� �� Request, �������� ������� ����� ��������� ���� ������� (���������).
     *
     * @var int
     */
    private $separator_var_name;

    /**
     * @param int $limit - ���������� ������� �� ������� ���� �� ��������
     * @param int $link_count - ���������� ������ �� �������� ����� �������� ����������, �.�.:
     *                          ���  ��  �  $link_count  �  ��  ���
     * @param mixed $request Krugozor_Http_Request|$_REQUEST - ������ ������� Krugozor_Http_Request ��� ���� ��
     *                                                         ��������������� �������� $_REQUEST, $_GET ��� $_POST.
     * @param string $page_var_name - ��� ����� ���������� �� �������, ����������� �������� ��� ��������.
     * @param string $separator_var_name - ��� ����� ���������� �� �������, ����������� ���� ������� (���������).
     * @return void
     */
    public function __construct($limit = 10, $link_count = 10, $request, $page_var_name = 'page', $separator_var_name = 'sep')
    {
        $this->limit = (int) $limit;
        $this->link_count = (int) $link_count;

        $this->page_var_name = (string) $page_var_name;
        $this->separator_var_name = (string) $separator_var_name;

        // ��� ����������.
        if (is_object($request) && $request instanceof Krugozor_Http_Request)
        {
            $this->current_sep  = $request->getRequest($separator_var_name, 'decimal') ?: 1;
            $this->current_page = $request->getRequest($page_var_name, 'decimal') ?: ($this->current_sep - 1) * $this->link_count + 1;
        }
        // ��� ��������� � ����� ��������� ���.
        else if (is_array($request))
        {
            $this->current_sep = !empty($request[$separator_var_name]) && is_numeric($request[$separator_var_name])
                                 ? intval($request[$separator_var_name])
                                 : 1;

            $this->current_page = !empty($request[$page_var_name]) && is_numeric($request[$page_var_name])
                                  ? intval($request[$page_var_name])
                                  : ($this->current_sep - 1) * $this->link_count + 1;
        }

        $this->start_limit = ($this->current_page - 1) * $this->limit;
        $this->stop_limit  = $this->limit;
    }

    /**
     * ���������� ��������� �������� ��� SQL-��������� LIMIT.
     *
     * @param void
     * @return int
     */
    public function getStartLimit()
    {
        return $this->start_limit;
    }

    /**
     * ���������� �������� �������� ��� SQL-��������� LIMIT.
     *
     * @param void
     * @return int
     */
    public function getStopLimit()
    {
        return $this->stop_limit;
    }

    /**
     * ���������� ����� ���������� �������.
     *
     * @param void
     * @return int
     */
    public function getCount()
    {
        return $this->total_rows;
    }

    /**
     * ��������� �������� �������� - ����� ���������� ������� � ����,
     * � ����� ��������� ��� ����������� ���������� ��� ������������ ������ ���������.
     *
     * � ������� ����������� �������� ������� ������, �� ����������� ������� ����� �� �������
     * � ���� �������� ��������� ������� ������. ������ ������, � ��� ��� �� � ��������� ������, ���
     * ��� ��������. �� ��� ��������!
     *
     * @param int
     * @return void
     */
    public function setCount($total_rows)
    {
        $this->total_rows = intval($total_rows);
        $this->total_pages = ceil($this->total_rows/$this->limit);
        $this->total_blocks = ceil($this->total_pages/$this->link_count);

        // ���� ���������� ������ ������ ���� �������, ��
        // �� ���������� ������ ���� ���������� ���� �������.
        $this->total_blocks = ($this->total_blocks > $this->total_pages) ? $this->total_pages : $this->total_blocks;

        // �������� ������ �������� ��� ������ � �������.
        $this->table = array();

        $k = ($this->current_sep - 1) * $this->link_count + 1;

            for ($i = $k; $i < $this->link_count + $k && $i <= $this->total_pages; $i++)
            {
                $temp = ($this->total_rows - (($i-1) * $this->limit));
                $temp2 = ($temp - $this->limit > 0) ? $temp - $this->limit + 1 : 1;

                $temp3 = ($this->limit * ($i - 1)) + 1;
                $temp4 = $i * $this->limit  > $this->total_rows ? $this->total_rows : $i * $this->limit;

                $this->table[] = array
                (
                    'page' => $i,
                    'separator' => $this->current_sep,
                    'decrement_anhor' => ($temp == $temp2 ? $temp : $temp . ' - ' . $temp2),
                    'increment_anhor' => ($temp3 == $temp4 ? $temp3 : $temp3 . ' - ' . $temp4)
                );
            }

        return $this;
    }

    /**
     * ���������� ����� ��� ������ ������� ������� ��� ������������ ���������.
     * � �����, ��� ������ �������, ������ ����� ����� ���������������� ���
     * ������ �������� �����.
     *
     * @param void
     * @return int
     */
    public function getAutodecrementNum()
    {
        return $this->total_rows - $this->start_limit;
    }

    /**
     * ���������� ����� ��� ������ ������� ������� ��� ������������ ���������.
     * � �����, ��� ������ �������, ������ ����� ����� ���������������� ���
     * ������ �������� �����.
     *
     * @param void
     * @return int
     */
    public function getAutoincrementNum()
    {
        return $this->limit * ($this->current_page-1) + 1;
    }

    /**
     * ���������� ����� ���������� ��� ������������ ������ (��).
     *
     * @param void
     * @return int
     */
    public function getPreviousBlockSeparator()
    {
        return $this->current_sep - 1 ?: 0;
    }

    /**
     * ���������� ����� ���������� ��� ������������ ������ (��).
     *
     * @param void
     * @return int
     */
    public function getNextBlockSeparator()
    {
        return $this->current_sep < $this->total_blocks ? $this->current_sep + 1 : 0;
    }

    /**
     * ���������� ����� ���������� ��� ������������ ������ (���).
     *
     * @param void
     * @return int
     */
    public function getLastSeparator()
    {
        return $this->total_blocks;
    }

    /**
     * ���������� ����� �������� ��� ������������ ������ (���).
     *
     * @param void
     * @return int
     */
    public function getLastPage()
    {
        return $this->total_pages;
    }

    /**
     * ���������� ����������� ������ ��� ����� ������ � ������� (��. ������).
     *
     * @param void
     * @return array
     */
    public function getTemplateData()
    {
        return $this->table;
    }

    /**
     * ���������� ����� ������� ��������.
     *
     * @param void
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * ���������� ����� �������� ����������.
     *
     * @param void
     * @return int
     */
    public function getCurrentSeparator()
    {
        return $this->current_sep;
    }

    /**
     * ���������� ����� ���������� ��� ������������ ������ (�).
     *
     * @param void
     * @return int
     */
    public function getPreviousPageSeparator()
    {
        // ������� ���������, ����������� ���������
        $cs = ceil($this->current_page / $this->link_count);
        // ���������� ��������� �������� current_page - 1
        $cs2 = ceil(($this->current_page - 1) / $this->link_count);

        // ���� ��������� �������� current_page - 1 ������ �������� ����������,
        // ������ �������� current_page - 1 ��������� � ���������� ����� � ����������� $cs2
        return $cs2 < $cs ? $cs2 : $cs;
    }

    /**
     * ���������� ����� ���������� ��� ������������ ������ (�).
     *
     * @param void
     * @return int
     */
    public function getNextPageSeparator()
    {
        // ������� ���������, ����������� ���������.
        $cs = ceil($this->current_page / $this->link_count);
        // ������������������� �������� current_page + 1.
        $cs2 = ceil(($this->current_page + 1) / $this->link_count);

        // ���� ��������� �������� current_page + 1 ������ �������� ����������,
        // ������ �������� current_page + 1 ��������� � ���������� ����� � ����������� $cs2.
        return $cs2 > $cs ? $cs2 : $cs;
    }

    /**
     * ���������� ����� �������� ��� ������������ ������ (�).
     *
     * @param void
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->current_page - 1 ?: 0;
    }

    /**
     * ���������� ����� �������� ��� ������������ ������ (��).
     *
     * @param void
     * @return int
     */
    public function getPageForPreviousBlock()
    {
        return $this->current_page - ($this->current_page % $this->link_count ?: $this->link_count);
    }

    /**
     * ���������� ����� �������� ��� ������������ ������ (�).
     *
     * @param void
     * @return int
     */
    public function getNextPage()
    {
        return $this->current_page < $this->total_pages ? $this->current_page + 1 : 0;
    }

    /**
     * ���������� ��� ���������� �� �������, ���������� ����� ����������.
     *
     * @param void
     * @return string
     */
    public function getSeparatorName()
    {
        return $this->separator_var_name;
    }

    /**
     * ���������� ��� ���������� �� �������, ���������� ����� ��������.
     *
     * @param void
     * @return string
     */
    public function getPageName()
    {
        return $this->page_var_name;
    }
}