import MindElixir from 'mind-elixir'
import { ru } from 'mind-elixir/i18n'
import 'mind-elixir/style.css'
import '../css/mind-maps.css'

const DEFAULT_TOPIC = 'New Mind Map'
const LIGHT_THEME = structuredClone(MindElixir.THEME)
const DARK_THEME = structuredClone(MindElixir.DARK_THEME)

function defaultData(topic = DEFAULT_TOPIC) {
    return MindElixir.new(topic)
}

function safeParse(json) {
    try {
        const parsed = JSON.parse(json)
        if (parsed && typeof parsed === 'object' && parsed.nodeData) {
            return parsed
        }
    } catch (error) {
        console.warn('Mind map JSON parse failed:', error)
    }

    return defaultData()
}

function syncTextarea(textarea, mind) {
    const nextValue = JSON.stringify(mind.getData(), null, 2)

    textarea.value = nextValue
    textarea.dispatchEvent(new Event('input', { bubbles: true }))
    textarea.dispatchEvent(new Event('change', { bubbles: true }))

    return nextValue
}

function flushActiveEdit(root, mind) {
    const active = document.activeElement

    if (active instanceof HTMLElement && root.contains(active) && active.isContentEditable) {
        active.blur()
        mind?.container?.focus?.()
    }
}

function forceSync(root, textarea, mind) {
    if (!textarea || !mind) {
        return
    }

    flushActiveEdit(root, mind)
    root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
}

function findTextarea(root) {
    return (
        root.closest('form')?.querySelector('textarea[data-mind-map-json="true"]') ??
        root.closest('form')?.querySelector('textarea[name$="[data]"]') ??
        root.closest('form')?.querySelector('textarea[id$="data"]') ??
        document.querySelector('textarea[data-mind-map-json="true"]') ??
        document.querySelector('textarea[name$="[data]"]') ??
        document.querySelector('textarea[id$="data"]') ??
        Array.from(document.querySelectorAll('textarea')).find((textarea) => {
            const value = textarea.value?.trim() ?? ''
            return value.startsWith('{') && value.includes('nodeData')
        }) ??
        null
    )
}

function showCanvasMessage(canvas, message, tone = 'muted') {
    canvas.innerHTML = `
        <div class="of-mind-map-editor__message of-mind-map-editor__message--${tone}">
            ${message}
        </div>
    `
}

function getRootTopicElement(mind) {
    return mind?.map?.querySelector('me-root > me-tpc') ?? null
}

function ensureSelection(mind) {
    if (mind?.currentNode) {
        return mind.currentNode
    }

    const root = getRootTopicElement(mind)

    if (root) {
        mind.selectNode(root)
    }

    return root
}

function getSelectedNodeObj(mind) {
    return mind?.currentNode?.nodeObj ?? null
}

function normalizeColor(value) {
    if (!value) {
        return ''
    }

    const sample = document.createElement('div')
    sample.style.color = value
    document.body.appendChild(sample)
    const computed = getComputedStyle(sample).color
    sample.remove()

    const match = computed.match(/\d+/g)
    if (!match || match.length < 3) {
        return ''
    }

    return `#${match
        .slice(0, 3)
        .map((part) => Number(part).toString(16).padStart(2, '0'))
        .join('')}`
}

function cleanNodeStyle(style = {}) {
    return Object.fromEntries(Object.entries(style).filter(([, value]) => value !== '' && value != null))
}

function findNodeById(node, id) {
    if (!node) {
        return null
    }

    if (node.id === id) {
        return node
    }

    for (const child of node.children ?? []) {
        const found = findNodeById(child, id)
        if (found) {
            return found
        }
    }

    return null
}

function updateStatus(root, mind) {
    const status = root.querySelector('[data-mind-map-status]')
    if (!status) {
        return
    }

    const current = getSelectedNodeObj(mind)
    if (!current) {
        status.textContent = 'No node selected'
        return
    }

    status.textContent = `Selected: ${current.topic || 'Untitled node'}`
}

function resolveTheme(mode) {
    if (mode === 'light') {
        return {
            ...structuredClone(LIGHT_THEME),
            type: 'light',
            name: 'Light',
        }
    }

    return {
        ...structuredClone(DARK_THEME),
        type: 'dark',
        name: 'Dark',
    }
}

function updateThemeButton(root, mode) {
    const button = root.querySelector('[data-mind-map-action="toggle-theme"]')
    if (button) {
        button.textContent = mode === 'light' ? 'Dark' : 'Light'
    }
}

function updateViewerThemeButton(root, mode) {
    const button = root.querySelector('[data-mind-map-viewer-action="toggle-theme"]')
    if (button) {
        button.textContent = mode === 'light' ? 'Dark' : 'Light'
    }
}

function scheduleScaleFit(mind, delay = 80) {
    window.setTimeout(() => {
        mind?.scaleFit?.()
        mind?.toCenter?.()
    }, delay)
}

function toggleInspector(root) {
    const shell = root.querySelector('[data-mind-map-shell]')
    if (!shell) {
        return
    }

    const isCollapsed = shell.classList.toggle('is-inspector-collapsed')
    root.dataset.mindMapInspector = isCollapsed ? 'collapsed' : 'expanded'
    scheduleScaleFit(root.__mind, 40)
}

function bindTopicBranchToggle(root, mind, textarea = null) {
    const topics = mind?.map?.querySelectorAll?.('me-tpc') ?? []

    topics.forEach((topic) => {
        if (topic.dataset.branchToggleBound === 'true') {
            return
        }

        topic.addEventListener('dblclick', (event) => {
            const nodeObj = topic.nodeObj

            if (!nodeObj?.children?.length) {
                return
            }

            event.preventDefault()
            event.stopPropagation()

            mind.selectNode(topic)
            mind.expandNode(topic)

            if (textarea) {
                forceSync(root, textarea, mind)
                updateInspector(root, mind)
            }
        })

        topic.dataset.branchToggleBound = 'true'
    })
}

function bindSaveSync(root, textarea) {
    const form = root.closest('form')

    if (!form || form.dataset.mindMapSyncBound === 'true') {
        return
    }

    form.addEventListener(
        'submit',
        () => {
            forceSync(root, textarea, root.__mind)
        },
        true,
    )

    form.querySelectorAll('button, [role="button"]').forEach((button) => {
        button.addEventListener(
            'click',
            () => {
                forceSync(root, textarea, root.__mind)
            },
            true,
        )
    })

    form.dataset.mindMapSyncBound = 'true'
}

function setActiveTab(root, tab) {
    root.querySelectorAll('[data-mind-map-tab]').forEach((button) => {
        button.classList.toggle('is-active', button.dataset.mindMapTab === tab)
    })

    root.querySelectorAll('[data-mind-map-panel]').forEach((panel) => {
        panel.classList.toggle('is-active', panel.dataset.mindMapPanel === tab)
    })
}

function updateIconButtons(root, icons = []) {
    root.querySelectorAll('[data-icon]').forEach((button) => {
        button.classList.toggle('is-active', icons.includes(button.dataset.icon))
    })
}

function updateInspector(root, mind) {
    const current = getSelectedNodeObj(mind)
    if (!current) {
        return
    }

    root.__inspectorSyncing = true

    const topic = root.querySelector('[data-inspector-field="topic"]')
    const color = root.querySelector('[data-inspector-field="color"]')
    const background = root.querySelector('[data-inspector-field="background"]')
    const fontSize = root.querySelector('[data-inspector-field="font-size"]')
    const tags = root.querySelector('[data-inspector-field="tags"]')
    const url = root.querySelector('[data-inspector-field="url"]')
    const imageUrl = root.querySelector('[data-inspector-field="image-url"]')
    const imageWidth = root.querySelector('[data-inspector-field="image-width"]')
    const imageHeight = root.querySelector('[data-inspector-field="image-height"]')
    const imageFit = root.querySelector('[data-inspector-field="image-fit"]')
    const note = root.querySelector('[data-inspector-field="note"]')

    if (topic) topic.value = current.topic ?? ''
    if (color) color.value = normalizeColor(current.style?.color) || '#ffffff'
    if (background) background.value = normalizeColor(current.style?.background) || '#4c4f69'
    if (fontSize) fontSize.value = parseInt(current.style?.fontSize || (current.parent ? '16' : '25'), 10)
    if (tags) tags.value = (current.tags ?? []).map((tag) => (typeof tag === 'string' ? tag : tag.text)).join(', ')
    if (url) url.value = current.hyperLink ?? ''
    if (imageUrl) imageUrl.value = current.image?.url ?? ''
    if (imageWidth) imageWidth.value = current.image?.width ?? ''
    if (imageHeight) imageHeight.value = current.image?.height ?? ''
    if (imageFit) imageFit.value = current.image?.fit ?? ''
    if (note) note.value = current.note ?? ''

    updateIconButtons(root, current.icons ?? [])
    updateStatus(root, mind)

    root.__inspectorSyncing = false
}

function mountEditor(root) {
    const canvas = root.querySelector('[data-mind-map-canvas]')
    const textarea = findTextarea(root)
    const shell = root.querySelector('[data-mind-map-shell]')
    const hasMountedMap = canvas?.querySelector('.map-container')

    if (!canvas) {
        return
    }

    if (!textarea) {
        showCanvasMessage(canvas, 'Editor is waiting for the JSON field to load...')
        return
    }

    bindSaveSync(root, textarea)

    const currentSource = textarea.value?.trim() ?? ''
    const renderedSource = root.dataset.mindMapRenderedSource?.trim() ?? ''

    if (root.dataset.mindMapReady === 'true' && root.__mind && hasMountedMap) {
        if (!currentSource || currentSource === renderedSource) {
            updateInspector(root, root.__mind)
            return
        }
    }

    const render = (data, selectedId = null) => {
        root.__mind?.destroy?.()
        canvas.innerHTML = ''
        root.dataset.mindMapReady = 'false'

        try {
            const mind = new MindElixir({
                el: canvas,
                direction: data.direction ?? MindElixir.SIDE,
                editable: true,
                contextMenu: {
                    locale: ru,
                    focus: true,
                    link: true,
                    extend: [
                        {
                            name: 'Add Parent',
                            key: 'Ctrl + Enter',
                            onclick: () => mind.insertParent(),
                        },
                        {
                            name: 'Move Up',
                            key: 'Alt + Up',
                            onclick: () => mind.moveUpNode(),
                        },
                        {
                            name: 'Move Down',
                            key: 'Alt + Down',
                            onclick: () => mind.moveDownNode(),
                        },
                        {
                            name: 'Summary',
                            onclick: () => mind.createSummary(),
                        },
                    ],
                },
                toolBar: false,
                keypress: true,
                allowUndo: true,
                overflowHidden: false,
                newTopicName: 'New Topic',
            })

            root.__mind = mind
            root.__mindRender = render

            mind.init(data)
            if (typeof mind.clearHistory === 'function') {
                mind.clearHistory()
            }

            if (selectedId) {
                try {
                    mind.selectNode(mind.findEle(selectedId))
                } catch {
                    ensureSelection(mind)
                }
            } else {
                ensureSelection(mind)
            }

            mind.bus.addListener('operation', () => {
                root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
                updateInspector(root, mind)
            })
            mind.bus.addListener('changeDirection', () => {
                root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
                updateInspector(root, mind)
            })
            mind.bus.addListener('selectNodes', () => updateInspector(root, mind))
            mind.bus.addListener('expandNode', () => {
                root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
                updateInspector(root, mind)
            })

            root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
            root.dataset.mindMapReady = 'true'
            root.dataset.mindMapTheme = data.theme?.type ?? 'dark'
            updateThemeButton(root, root.dataset.mindMapTheme)
            setActiveTab(root, root.dataset.mindMapTab || 'general')
            updateInspector(root, mind)
            bindTopicBranchToggle(root, mind, textarea)
            if (root.dataset.mindMapInspector === 'collapsed') {
                shell?.classList.add('is-inspector-collapsed')
            } else {
                shell?.classList.remove('is-inspector-collapsed')
            }
            scheduleScaleFit(mind, 30)
        } catch (error) {
            console.error('Mind map editor mount failed:', error)
            showCanvasMessage(canvas, 'Mind map editor failed to initialize. Try reloading the page.', 'error')
        }
    }

    if (root.dataset.mindMapControlsBound !== 'true') {
        const rerenderWithSelectedNode = (mutator) => {
            const mind = root.__mind
            if (!mind) return

            const selectedId = getSelectedNodeObj(mind)?.id ?? null
            const data = mind.getData()
            const target = selectedId ? findNodeById(data.nodeData, selectedId) : data.nodeData

            if (!target) {
                return
            }

            mutator(target, data, mind)
            render(data, selectedId)
        }

        root.querySelectorAll('[data-mind-map-tab]').forEach((button) => {
            button.addEventListener('click', () => {
                root.dataset.mindMapTab = button.dataset.mindMapTab
                setActiveTab(root, button.dataset.mindMapTab)
            })
        })

        root.querySelector('[data-mind-map-action="reload"]')?.addEventListener('click', () => {
            render(safeParse(textarea.value), getSelectedNodeObj(root.__mind)?.id ?? null)
        })

        root.querySelector('[data-mind-map-action="reset"]')?.addEventListener('click', () => {
            render(defaultData(), null)
        })

        root.querySelector('[data-mind-map-action="submit-form"]')?.addEventListener('click', () => {
            forceSync(root, textarea, root.__mind)
            root.closest('form')?.requestSubmit?.()
        })

        root.querySelector('[data-mind-map-action="toggle-theme"]')?.addEventListener('click', () => {
            const mind = root.__mind
            if (!mind) return

            flushActiveEdit(root, mind)
            const nextMode = (root.dataset.mindMapTheme ?? 'dark') === 'dark' ? 'light' : 'dark'
            const theme = resolveTheme(nextMode)
            mind.changeTheme(theme)
            root.dataset.mindMapTheme = nextMode
            updateThemeButton(root, nextMode)
            root.dataset.mindMapRenderedSource = syncTextarea(textarea, mind)
            updateInspector(root, mind)
            scheduleScaleFit(mind, 40)
        })

        root.querySelector('[data-mind-map-action="toggle-fullscreen"]')?.addEventListener('click', async () => {
            if (!shell) return
            if (document.fullscreenElement === shell) {
                await document.exitFullscreen()
            } else {
                await shell.requestFullscreen()
            }
            scheduleScaleFit(root.__mind, 120)
        })

        root.querySelector('[data-mind-map-action="exit-fullscreen"]')?.addEventListener('click', async () => {
            if (document.fullscreenElement) {
                await document.exitFullscreen()
            }
            scheduleScaleFit(root.__mind, 120)
        })

        root.querySelectorAll('[data-mind-map-action="toggle-inspector"]').forEach((button) => {
            button.addEventListener('click', () => toggleInspector(root))
        })

        root.querySelector('[data-mind-map-action="scale-fit"]')?.addEventListener('click', () => root.__mind?.scaleFit?.())
        root.querySelector('[data-mind-map-action="zoom-in"]')?.addEventListener('click', () => {
            const mind = root.__mind
            if (mind) mind.scale(Math.min((mind.scaleVal ?? 1) + 0.1, mind.scaleMax ?? 1.4))
        })
        root.querySelector('[data-mind-map-action="zoom-out"]')?.addEventListener('click', () => {
            const mind = root.__mind
            if (mind) mind.scale(Math.max((mind.scaleVal ?? 1) - 0.1, mind.scaleMin ?? 0.2))
        })
        root.querySelector('[data-mind-map-action="center"]')?.addEventListener('click', () => root.__mind?.toCenter?.())
        root.querySelector('[data-mind-map-action="layout-side"]')?.addEventListener('click', () => root.__mind?.initSide?.())
        root.querySelector('[data-mind-map-action="layout-left"]')?.addEventListener('click', () => root.__mind?.initLeft?.())
        root.querySelector('[data-mind-map-action="layout-right"]')?.addEventListener('click', () => root.__mind?.initRight?.())
        root.querySelector('[data-mind-map-action="toggle-compact"]')?.addEventListener('click', () => {
            rerenderWithSelectedNode((target, data) => {
                data.compact = !data.compact
            })
        })
        root.querySelector('[data-mind-map-action="toggle-branch"]')?.addEventListener('click', () => {
            const mind = root.__mind
            const node = ensureSelection(mind)
            if (node) {
                mind.expandNode(node)
                forceSync(root, textarea, mind)
                updateInspector(root, mind)
            }
        })

        root.querySelector('[data-mind-map-action="edit-root"]')?.addEventListener('click', async () => {
            const node = ensureSelection(root.__mind)
            if (node) {
                await root.__mind.beginEdit(node)
            }
        })
        root.querySelector('[data-mind-map-action="add-child"]')?.addEventListener('click', async () => {
            ensureSelection(root.__mind)
            await root.__mind?.addChild()
            forceSync(root, textarea, root.__mind)
            updateInspector(root, root.__mind)
        })
        root.querySelector('[data-mind-map-action="add-sibling"]')?.addEventListener('click', async () => {
            ensureSelection(root.__mind)
            await root.__mind?.insertSibling('after')
            forceSync(root, textarea, root.__mind)
            updateInspector(root, root.__mind)
        })
        root.querySelector('[data-mind-map-action="add-parent"]')?.addEventListener('click', async () => {
            ensureSelection(root.__mind)
            await root.__mind?.insertParent()
            forceSync(root, textarea, root.__mind)
            updateInspector(root, root.__mind)
        })
        root.querySelector('[data-mind-map-action="move-up"]')?.addEventListener('click', async () => {
            await root.__mind?.moveUpNode()
            forceSync(root, textarea, root.__mind)
            updateInspector(root, root.__mind)
        })
        root.querySelector('[data-mind-map-action="move-down"]')?.addEventListener('click', async () => {
            await root.__mind?.moveDownNode()
            forceSync(root, textarea, root.__mind)
            updateInspector(root, root.__mind)
        })
        root.querySelector('[data-mind-map-action="remove-selected"]')?.addEventListener('click', async () => {
            if (root.__mind?.currentNodes?.length) {
                await root.__mind.removeNodes(root.__mind.currentNodes)
                ensureSelection(root.__mind)
                forceSync(root, textarea, root.__mind)
                updateInspector(root, root.__mind)
            }
        })

        root.querySelector('[data-mind-map-action="apply-image"]')?.addEventListener('click', () => {
            rerenderWithSelectedNode((target) => {
                const url = root.querySelector('[data-inspector-field="image-url"]')?.value?.trim() ?? ''
                const width = parseInt(root.querySelector('[data-inspector-field="image-width"]')?.value || '0', 10)
                const height = parseInt(root.querySelector('[data-inspector-field="image-height"]')?.value || '0', 10)
                const fit = root.querySelector('[data-inspector-field="image-fit"]')?.value || undefined

                if (!url || !width || !height) {
                    delete target.image
                    return
                }

                target.image = {
                    url,
                    width,
                    height,
                    fit,
                }
            })
        })

        root.querySelector('[data-mind-map-action="clear-image"]')?.addEventListener('click', () => {
            rerenderWithSelectedNode((target) => {
                delete target.image
            })
        })

        root.querySelectorAll('[data-icon]').forEach((button) => {
            button.addEventListener('click', () => {
                rerenderWithSelectedNode((target) => {
                    const icon = button.dataset.icon
                    const icons = [...(target.icons ?? [])]
                    const index = icons.indexOf(icon)

                    if (index === -1) {
                        icons.push(icon)
                    } else {
                        icons.splice(index, 1)
                    }

                    target.icons = icons.length ? icons : undefined
                })
            })
        })

        const onFieldChange = (selector, callback, eventName = 'change') => {
            root.querySelector(selector)?.addEventListener(eventName, () => {
                if (root.__inspectorSyncing) {
                    return
                }

                rerenderWithSelectedNode(callback)
            })
        }

        onFieldChange('[data-inspector-field="topic"]', (target) => {
            const value = root.querySelector('[data-inspector-field="topic"]')?.value?.trim() ?? ''
            target.topic = value || 'Untitled'
        }, 'blur')

        onFieldChange('[data-inspector-field="color"]', (target) => {
            target.style = cleanNodeStyle({
                ...(target.style ?? {}),
                color: root.querySelector('[data-inspector-field="color"]')?.value || '',
            })
        })

        onFieldChange('[data-inspector-field="background"]', (target) => {
            target.style = cleanNodeStyle({
                ...(target.style ?? {}),
                background: root.querySelector('[data-inspector-field="background"]')?.value || '',
            })
        })

        onFieldChange('[data-inspector-field="font-size"]', (target) => {
            target.style = cleanNodeStyle({
                ...(target.style ?? {}),
                fontSize: `${root.querySelector('[data-inspector-field="font-size"]')?.value || '16'}px`,
            })
        }, 'input')

        onFieldChange('[data-inspector-field="tags"]', (target) => {
            const raw = root.querySelector('[data-inspector-field="tags"]')?.value || ''
            const tags = raw
                .split(',')
                .map((item) => item.trim())
                .filter(Boolean)
            target.tags = tags.length ? tags : undefined
        }, 'blur')

        onFieldChange('[data-inspector-field="url"]', (target) => {
            const value = root.querySelector('[data-inspector-field="url"]')?.value?.trim() ?? ''
            target.hyperLink = value || undefined
        }, 'blur')

        onFieldChange('[data-inspector-field="note"]', (target) => {
            const value = root.querySelector('[data-inspector-field="note"]')?.value ?? ''
            target.note = value.trim() ? value : undefined
        }, 'blur')

        root.dataset.mindMapControlsBound = 'true'
    }

    render(safeParse(textarea.value), getSelectedNodeObj(root.__mind)?.id ?? null)
}

function mountViewer(root) {
    if (root.dataset.mindMapReady === 'true') {
        return
    }

    const canvas = root.querySelector('[data-mind-map-canvas]')
    const raw = root.dataset.mindMap

    if (!canvas || !raw) {
        return
    }

    let data = null
    try {
        data = JSON.parse(raw)
    } catch (error) {
        console.warn('Mind map viewer JSON parse failed:', error)
        return
    }

    if (!data?.nodeData) {
        return
    }

    try {
        const mind = new MindElixir({
            el: canvas,
            direction: data.direction ?? MindElixir.SIDE,
            editable: false,
            contextMenu: false,
            toolBar: false,
            keypress: false,
            allowUndo: false,
            overflowHidden: false,
        })

        mind.init(data)
        root.dataset.mindMapReady = 'true'
        root.__mind = mind
        root.dataset.mindMapTheme = data.theme?.type ?? 'dark'
        updateViewerThemeButton(root, root.dataset.mindMapTheme)
        bindTopicBranchToggle(root, mind)
        scheduleScaleFit(mind, 30)

        if (root.dataset.mindMapViewerBound !== 'true') {
            root.querySelector('[data-mind-map-viewer-action="toggle-theme"]')?.addEventListener('click', () => {
                const nextMode = (root.dataset.mindMapTheme ?? 'dark') === 'dark' ? 'light' : 'dark'
                const theme = resolveTheme(nextMode)
                root.__mind?.changeTheme(theme)
                root.dataset.mindMapTheme = nextMode
                updateViewerThemeButton(root, nextMode)
                scheduleScaleFit(root.__mind, 40)
            })

            root.querySelector('[data-mind-map-viewer-action="scale-fit"]')?.addEventListener('click', () => {
                root.__mind?.scaleFit?.()
                root.__mind?.toCenter?.()
            })

            root.querySelector('[data-mind-map-viewer-action="zoom-in"]')?.addEventListener('click', () => {
                const currentMind = root.__mind
                if (currentMind) currentMind.scale(Math.min((currentMind.scaleVal ?? 1) + 0.1, currentMind.scaleMax ?? 1.4))
            })

            root.querySelector('[data-mind-map-viewer-action="zoom-out"]')?.addEventListener('click', () => {
                const currentMind = root.__mind
                if (currentMind) currentMind.scale(Math.max((currentMind.scaleVal ?? 1) - 0.1, currentMind.scaleMin ?? 0.2))
            })

            root.querySelector('[data-mind-map-viewer-action="toggle-fullscreen"]')?.addEventListener('click', async () => {
                if (document.fullscreenElement === root) {
                    await document.exitFullscreen()
                } else {
                    await root.requestFullscreen()
                }
                scheduleScaleFit(root.__mind, 120)
            })

            root.dataset.mindMapViewerBound = 'true'
        }
    } catch (error) {
        console.error('Mind map viewer mount failed:', error)
    }
}

function initMindMaps() {
    document.querySelectorAll('[data-mind-map-editor]').forEach(mountEditor)
    document.querySelectorAll('[data-mind-map-viewer]').forEach(mountViewer)
}

let initTimer = null

function scheduleInitMindMaps() {
    window.clearTimeout(initTimer)
    initTimer = window.setTimeout(initMindMaps, 60)
}

initMindMaps()
document.addEventListener('DOMContentLoaded', initMindMaps)
document.addEventListener('livewire:navigated', initMindMaps)
document.addEventListener('livewire:initialized', initMindMaps)
window.setTimeout(initMindMaps, 150)
window.setTimeout(initMindMaps, 600)

const observer = new MutationObserver(() => {
    scheduleInitMindMaps()
})

observer.observe(document.body, {
    childList: true,
    subtree: true,
})
