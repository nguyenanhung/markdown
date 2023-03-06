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

use Exception;
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
    const VERSION = '2.0.2';

    public function __construct()
    {
        try {
            parent::__construct();
            array_unshift($this->BlockTypes['['], 'Checkbox');
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                $errorMessage = 'Error Code:' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
                log_message('error', $errorMessage);
                log_message('error', $e->getTraceAsString());
            }
        }
    }

    /**
     * This file is part of the ParsedownCheckbox package.
     *
     * (c) Simon Leblanc <contact@leblanc-simon.eu>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     *
     * @param $line
     *
     * @return array|void
     */
    protected function blockCheckbox($line)
    {
        $text = trim($line['text']);
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
     */
    protected function blockListComplete(array $Block): array
    {
        if (isset($Block['element']['elements'])) {
            foreach ($Block['element']['elements'] as &$li_element) {
                if (isset($li_element['handler']['argument'])) {
                    foreach ($li_element['handler']['argument'] as $text) {
                        $begin_line = substr(trim($text), 0, 4);
                        if ('[ ] ' === $begin_line) {
                            $li_element['attributes'] = ['class' => 'parsedown-task-list parsedown-task-list-open'];
                        } elseif ('[x] ' === $begin_line) {
                            $li_element['attributes'] = ['class' => 'parsedown-task-list parsedown-task-list-close'];
                        }
                    }
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
     */
    protected function blockCheckboxComplete(array $block): array
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
     */
    protected function checkboxUnchecked($text): string
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
     */
    protected function checkboxChecked($text): string
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
     */
    protected function format($text): string
    {
        // backup settings
        $markup_escaped = $this->markupEscaped;
        $safe_mode = $this->safeMode;

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
