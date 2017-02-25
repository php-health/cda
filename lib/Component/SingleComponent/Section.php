<?php
/*
 * The MIT License
 *
 * Copyright 2017 Julien Fastré <julien.fastre@champs-libres.coop>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace PHPHealth\CDA\Component\SingleComponent;

use PHPHealth\CDA\Elements\AbstractElement;
use PHPHealth\CDA\HasClassCode;
use PHPHealth\CDA\Elements\TemplateId;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\Elements\Id;
use PHPHealth\CDA\Elements\Title;
use PHPHealth\CDA\DataType\Code\CodedWithEquivalents;
use PHPHealth\CDA\Elements\Code;
use PHPHealth\CDA\DataType\TextAndMultimedia\CharacterString;
use PHPHealth\CDA\Elements\Text;
use PHPHealth\CDA\Elements\Entry;

/**
 * 
 * Single section which will be included in component
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Section extends AbstractElement implements HasClassCode
{
    /**
     *
     * @var InstanceIdentifier
     */
    private $id;
    
    /**
     *
     * @var CodedWithEquivalents
     */
    private $code;
    
    /**
     * 
     * @var CharacterString
     */
    private $text;
    
    /**
     *
     * @var InstanceIdentifier[]
     */
    private $templateIds = array();
    
    /**
     *
     * @var CharacterString
     */
    private $title;
    
    /**
     *
     * @var Entry[]
     */
    private $entries = array();
    
    protected function getElementTag(): string
    {
        return 'section';
    }

    public function getClassCode(): string
    {
        return 'DOCSECT';
    }
    
    /**
     * 
     * @return InstanceIdentifier
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(InstanceIdentifier $id)
    {
        $this->id = $id;
        
        return $this;
    }

    public function getCode(): CodedWithEquivalents
    {
        return $this->code;
    }

    public function setCode(CodedWithEquivalents $code)
    {
        $this->code = $code;
        return $this;
    }
    
    public function getText(): CharacterString
    {
        return $this->text;
    }

    public function setText(CharacterString $text)
    {
        $this->text = $text;
        return $this;
    }
    
    public function setTemplateIds(array $templateIds)
    {
        $validation = \array_reduce($templateIds, 
            function ($carry, $item) {
                if ($carry === false) {
                    return false;
                }
                
                return $item instanceof InstanceIdentifier;
            });
        
        if ($validation === false) {
            throw new \UnexpectedValueException(sprintf("The values of templateIds"
                . " must contains only %s", InstanceIdentifier::class));
        }
        
        $this->templateIds = $templateIds;
        
        return $this;
    }
    
    public function addTemplateId(InstanceIdentifier $templateId)
    {
        $this->templateIds[] = $templateId;
        
        return $this;
    }

            
    /**
     * the code for the current section
     * 
     * @return InstanceIdentifier[]
     */
    public function getTemplateIds()
    {
        return $this->templateIds;
    }
    
    /**
     * The title for the section
     * 
     * @return CharacterString
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle(CharacterString $title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * create an entry, which is already bound to the current section
     * 
     * @return Entry
     */
    public function createEntry(): Entry
    {
        $entry = new Entry();
        
        $this->addEntry($entry);
        
        return $entry;
    }
    
    function getEntries(): array
    {
        return $this->entries;
    }
    
    public function addEntry(Entry $entry)
    {
        $this->entries[] = $entry;
    }

    
    /**
     * 
     * @param \DOMDocument $doc
     */
    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        // append templateId
        if ($this->getTemplateIds() !== NULL) {
            foreach ($this->getTemplateIds() as $id) {
                $el->appendChild(
                    (new TemplateId($id))->toDOMElement($doc)
                    );
            }
        }
        // append id
        if ($this->getId() !== NULL) {
            $el->appendChild(
                (new Id($this->getId()))->toDOMElement($doc)
                );
        }
        // append code
        if ($this->getCode() !== NULL) {
            $el->appendChild(
                (new Code($this->getCode()))->toDOMElement($doc)
                );
        }
        // append title
        if (! empty($this->getTitle())) {
            $el->appendChild(
                (new Title($this->getTitle()))->toDOMElement($doc)
                );
        }
        // append text
        if (! empty($this->getText())) {
            $el->appendChild(
                (new Text($this->getText()))->toDOMElement($doc)
                );
        }
        
        foreach ($this->getEntries() as $entry) {
            $el->appendChild($entry->toDOMElement($doc));
        }
        
        return $el;
    }
}
