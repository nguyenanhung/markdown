<?php
/**
 * Project markdown
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/11/2021
 * Time: 10:24
 */

namespace nguyenanhung\Markdown;

use ParsedownExtra;

/**
 * Class MarkdownParse
 *
 * @package   nguyenanhung\Markdown
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class MarkdownParse extends ParsedownExtra
{
    const VERSION = '0.2.0';

    public function __construct()
    {
        parent::__construct();

        array_unshift($this->BlockTypes['['], 'Checkbox');
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function blockCheckbox
     *
     * @param $line
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 26:38
     */
    protected function blockCheckbox($line)
    {
        $text       = trim($line['text']);
        $begin_line = substr($text, 0, 4);
        if ('[ ] ' === $begin_line) {
            return [
                'handler' => 'checkboxUnchecked',
                'text'    => substr(trim($text), 4),
            ];
        }

        if ('[x] ' === $begin_line) {
            return [
                'handler' => 'checkboxChecked',
                'text'    => substr(trim($text), 4),
            ];
        }
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function blockListComplete
     *
     * @param array $blocks
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 27:00
     */
    protected function blockListComplete(array $Block)
    {
        foreach ($Block['element']['elements'] as &$li_element) {
            foreach ($li_element['handler']['argument'] as $text) {
                $begin_line = substr(trim($text), 0, 4);
                if ('[ ] ' === $begin_line) {
                    $li_element['attributes'] = ['class' => 'parsedown-task-list parsedown-task-list-open'];
                } elseif ('[x] ' === $begin_line) {
                    $li_element['attributes'] = ['class' => 'parsedown-task-list parsedown-task-list-close'];
                }
            }
        }

        return $Block;
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function blockCheckboxContinue
     *
     * @param array $block
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 27:11
     */
    protected function blockCheckboxContinue(array $block)
    {
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function blockCheckboxComplete
     *
     * @param array $block
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 27:18
     */
    protected function blockCheckboxComplete(array $block)
    {
        $block['element'] = [
            'rawHtml'                => $this->{$block['handler']}($block['text']),
            'allowRawHtmlInSafeMode' => true,
        ];

        return $block;
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function checkboxUnchecked
     *
     * @param $text
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 27:39
     */
    protected function checkboxUnchecked($text)
    {
        if ($this->markupEscaped || $this->safeMode) {
            $text = self::escape($text);
        }

        return '<input type="checkbox" disabled /> ' . $this->format($text);
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Function checkboxChecked
     *
     * @param $text
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/11/2021 27:48
     */
    protected function checkboxChecked($text)
    {
        if ($this->markupEscaped || $this->safeMode) {
            $text = self::escape($text);
        }

        return '<input type="checkbox" checked disabled /> ' . $this->format($text);
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     * ------------------------------------------------------------------------
     * Formats the checkbox label without double escaping.
     *
     * @param string $text the string to format
     *
     * @return string the formatted text
     */
    protected function format($text)
    {
        // backup settings
        $markup_escaped = $this->markupEscaped;
        $safe_mode      = $this->safeMode;

        // disable rules to prevent double escaping.
        $this->setMarkupEscaped(false);
        $this->setSafeMode(false);

        // format line
        $text = $this->line($text);

        // reset old values
        $this->setMarkupEscaped($markup_escaped);
        $this->setSafeMode($safe_mode);

        return $text;
    }
}