<template>
  <div
    role="search"
    aria-label="Site search"
      class="fixed left-1/2 bottom-[calc(1rem+env(safe-area-inset-bottom))] -translate-x-1/2 z-50 transition-all duration-500 ease-in-out"
      :class="showAnswer ? 'w-[min(500px,calc(100vw-2rem))] sm:w-[min(500px,65vw)]' : isExpanded ? 'w-[min(405px,calc(100vw-2rem))] sm:w-[min(405px,52vw)]' : 'w-[min(320px,calc(100vw-2rem))] sm:w-80'"
  >
    <!-- Answer Modal -->
    <div
      v-if="showAnswer"
      class="absolute bottom-full left-0 right-0 mb-4 p-4 rounded-2xl border border-white/30 transition-all duration-500 ease-out transform-gpu"
      :class="showAnswer ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-4 scale-95'"
      :style="{
        background: 'rgba(0, 0, 0, 0.5)',
        backdropFilter: 'blur(40px) saturate(180%)',
        WebkitBackdropFilter: 'blur(40px) saturate(180%)',
        boxShadow: '0px 20px 80px rgba(0, 0, 0, 0.8), 0 0 0 0.5px rgba(255, 255, 255, 0.5) inset, 0 1px 3px rgba(255, 255, 255, 0.25) inset'
      }"
    >
      <!-- Loading Skeletons -->
      <div v-if="isLoading" class="space-y-3 min-h-[8rem]">
        <div class="h-4 bg-white/20 rounded skeleton-wave skeleton-1"></div>
        <div class="h-4 bg-white/20 rounded skeleton-wave skeleton-1 w-4/5"></div>
        <div class="h-4 bg-white/20 rounded skeleton-wave skeleton-1 w-3/4"></div>
        <div class="h-4 bg-white/20 rounded skeleton-wave skeleton-1 w-5/6"></div>
        <div class="h-4 bg-white/20 rounded skeleton-wave skeleton-1 w-3/5"></div>
      </div>
      
      <!-- Answer Content -->
      <div v-else class="text-white text-xs leading-relaxed min-h-[8rem] max-h-[20rem] overflow-y-auto transition-all duration-300" :style="{ textShadow: '0px 2px 8px rgba(0, 0, 0, 0.8)' }">
        <p v-if="displayedTitle" class="mb-2 font-medium">{{ displayedTitle }}</p>
        <div class="text-white/90 prose prose-invert max-w-none">
          <div v-html="processedContent"></div>
        </div>
      </div>
      
      <!-- Close button -->
      <button
        @click="closeAnswer"
        class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
        style="touch-action: manipulation;"
      >
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <form
      @submit.prevent="handleSubmit"
      class="group relative flex items-center gap-3 rounded-full border border-white/30 transition-all duration-500 ease-in-out overflow-hidden hover-scale"
      :class="[
        showAnswer && 'answer-active',
        animationClass
      ]"
      :style="{
        background: 'rgba(0, 0, 0, 0.5)',
        backdropFilter: 'blur(40px) saturate(180%)',
        WebkitBackdropFilter: 'blur(40px) saturate(180%)',
        boxShadow: '0px 20px 80px rgba(0, 0, 0, 0.8), 0 0 0 0.5px rgba(255, 255, 255, 0.5) inset, 0 1px 3px rgba(255, 255, 255, 0.25) inset'
      }"
    >
      <!-- Gradient border layer 1 (screen blend) -->
      <span
        class="absolute inset-0 rounded-full pointer-events-none"
        :style="{
          mixBlendMode: 'screen',
          opacity: 0.2,
          padding: '1.5px',
          WebkitMask: 'linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0)',
          WebkitMaskComposite: 'xor',
          maskComposite: 'exclude',
          background: 'linear-gradient(135deg, rgba(255, 255, 255, 0.0) 0%, rgba(255, 255, 255, 0.12) 33%, rgba(255, 255, 255, 0.4) 66%, rgba(255, 255, 255, 0.0) 100%)'
        }"
      ></span>
      
      <!-- Gradient border layer 2 (overlay blend) -->
      <span
        class="absolute inset-0 rounded-full pointer-events-none"
        :style="{
          mixBlendMode: 'overlay',
          padding: '1.5px',
          WebkitMask: 'linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0)',
          WebkitMaskComposite: 'xor',
          maskComposite: 'exclude',
          background: 'linear-gradient(135deg, rgba(255, 255, 255, 0.0) 0%, rgba(255, 255, 255, 0.32) 33%, rgba(255, 255, 255, 0.6) 66%, rgba(255, 255, 255, 0.0) 100%)'
        }"
      ></span>

      <input
        ref="inputRef"
        type="search"
        autocomplete="off"
        v-model="query"
        :placeholder="placeholder"
        @focus="handleFocus"
        @blur="handleBlur"
        class="flex-1 bg-transparent text-white placeholder:text-white/80 py-3.5 px-4 pr-12 focus:outline-none text-base sm:text-[15px] search-input-clean relative z-10"
        :style="{
          textShadow: '0px 2px 8px rgba(0, 0, 0, 0.8)'
        }"
      />

      <!-- Submit / open button -->
      <button
        type="submit"
        aria-label="Search"
        class="relative z-10 mr-1.5 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white dark:bg-neutral-800 border border-white/60 dark:border-white/10 shadow-sm transition-transform active:scale-95 after:pointer-events-none after:absolute after:inset-0 after:rounded-full after:ring-1 after:ring-black/5 dark:after:ring-white/5"
        style="touch-action: manipulation;"
      >
        <svg
          class="h-4 w-4 text-neutral-800"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <!-- clean, rounded arrow-up (Heroicons/Lucide style) -->
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.25"
            d="M12 19V5m0 0l-6 6m6-6l6 6"
          />
        </svg>
      </button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'FloatingAskBar',
  props: {
    placeholder: {
      type: String,
      default: 'Ask a question…'
    },
    openPalette: {
      type: Function,
      default: null
    },
    apiUrl: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      query: '',
      animationClass: 'animate-fade-in-up',
      isExpanded: false,
      showAnswer: false,
      isLoading: false,
      answerTitle: '',
      answerContent: '',
      displayedTitle: '',
      displayedContent: '',
      isStreaming: false,
      streamingIntervals: [],
      abortController: null
    };
  },
  computed: {
    effectiveApiUrl() {
      // 1. Use prop if explicitly provided
      if (this.apiUrl) {
        return this.apiUrl;
      }
      
      // 2. Production: use relative path to chatbot-api
      if (window.location.hostname.includes('colby.edu')) {
        return '/chatbot-api';
      }
      
      // 3. Platform.sh preview environment (contains platformsh.site)
      if (window.location.hostname.includes('platformsh.site')) {
        return '/chatbot-api';
      }
      
      // 4. Local development
      return 'http://localhost:7777';
    },
    processedContent() {
      // Append cursor before markdown processing so it stays inline
      const contentWithCursor = this.displayedContent + (this.isStreaming ? '|' : '');
      return this.processMarkdown(contentWithCursor);
    }
  },
  mounted() {
    // Trigger animation on mount
    setTimeout(() => {
      this.animationClass = 'opacity-100 translate-y-0';
    }, 100);
  },
  beforeUnmount() {
    // Clean up any ongoing requests
    if (this.abortController) {
      this.abortController.abort();
    }
    this.clearStreaming();
  },
  methods: {
    handleSubmit() {
      if (this.query.trim()) {
        this.showAnswerModal();
      } else if (this.openPalette) {
        this.openPalette();
      } else {
        this.$emit('submit', this.query);
        // Trigger the existing search modal
        if (this.emitter) {
          this.emitter.emit('open-modal');
        }
      }
    },
    async showAnswerModal() {
      this.showAnswer = true;
      this.isLoading = true;
      this.isStreaming = false;
      
      try {
        await this.callLocalAPI(this.query);
      } catch (error) {
        console.error('API Error:', error);
        this.isLoading = false;
        this.answerTitle = 'Error';
        
        // Provide more specific error messages for debugging
        let errorMessage = 'Sorry, there was an error processing your request.';
        
        if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
          errorMessage = 'Network error: Unable to connect to the API. Check if ngrok is running and the URL is correct.';
        } else if (error.message.includes('404')) {
          errorMessage = 'API endpoint not found (404). Please check that the /ask/stream endpoint exists and the URL is correct.';
        } else if (error.message.includes('CORS')) {
          errorMessage = 'CORS error: The API server needs to allow requests from this domain.';
        } else {
          errorMessage += ' Error: ' + error.message;
        }
        
        this.answerContent = errorMessage;
        this.startStreaming();
      }
    },
    
    async callLocalAPI(message) {
      // Use new /ask/stream endpoint
      const url = `${this.effectiveApiUrl}/ask/stream`;
      
      // Create abort controller for cleanup
      this.abortController = new AbortController();
      
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Accept': 'text/event-stream',
            'Content-Type': 'application/json',
            'ngrok-skip-browser-warning': 'true'
          },
          body: JSON.stringify({ message: message }),
          signal: this.abortController.signal
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Handle SSE streaming
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        
        // Keep loading skeleton until we get first content
        // this.isLoading = false; // Don't set to false yet
        this.answerTitle = '';
        this.answerContent = '';
        this.displayedTitle = '';
        this.displayedContent = '';
        this.isStreaming = false; // Will be set to true when we get first content
        
        while (true) {
          const { done, value } = await reader.read();
          if (done) break;
          
          const chunk = decoder.decode(value, { stream: true });
          const lines = chunk.split('\n');
          
          for (const line of lines) {
            if (!line.trim()) continue;
            
            if (line.startsWith('data: ')) {
              const payload = line.slice(6);
              let contentToAdd = '';
              
              try {
                const data = JSON.parse(payload);
                const content = data.content;
                if (content) {
                  contentToAdd = content;
                }
              } catch (jsonError) {
                // If it's not JSON, use the raw payload as fallback (like Python code)
                if (payload.trim() && payload !== '[DONE]') {
                  contentToAdd = payload;
                }
              }
              
              // Add content if we have any
              if (contentToAdd) {
                // Hide loading skeleton on first content received
                if (this.isLoading) {
                  this.isLoading = false;
                  this.isStreaming = true;
                }
                
                this.displayedContent += contentToAdd;
                
                // Auto-scroll to bottom as content appears
                this.$nextTick(() => {
                  const scrollContainer = this.$el.querySelector('.overflow-y-auto');
                  if (scrollContainer) {
                    scrollContainer.scrollTop = scrollContainer.scrollHeight;
                  }
                });
              }
            }
          }
        }
        
        this.isStreaming = false;
        
      } catch (error) {
        throw error;
      }
    },
    closeAnswer() {
      // Abort any ongoing API request
      if (this.abortController) {
        this.abortController.abort();
        this.abortController = null;
      }
      
      this.clearStreaming();
      this.showAnswer = false;
      this.isLoading = false;
      this.answerTitle = '';
      this.answerContent = '';
      this.displayedTitle = '';
      this.displayedContent = '';
    },
    handleFocus() {
      this.isExpanded = true;
    },
    handleBlur() {
      // Only collapse if there's no query text
      if (!this.query.trim()) {
        this.isExpanded = false;
      }
    },
    focusInput() {
      this.$refs.inputRef?.focus();
    },
    startStreaming() {
      this.displayedTitle = '';
      this.displayedContent = '';
      this.isStreaming = true;
      
      // Stream the content directly (no title)
      this.streamText(this.answerContent, 'displayedContent', 15, () => {
        this.isStreaming = false;
      });
    },
    streamText(text, targetProperty, speed, callback) {
      let index = 0;
      const interval = setInterval(() => {
        if (index < text.length) {
          this[targetProperty] += text[index];
          index++;
          
          // Auto-scroll to bottom as content appears
          this.$nextTick(() => {
            const scrollContainer = this.$el.querySelector('.overflow-y-auto');
            if (scrollContainer) {
              scrollContainer.scrollTop = scrollContainer.scrollHeight;
            }
          });
        } else {
          clearInterval(interval);
          if (callback) callback();
        }
      }, speed);
      
      this.streamingIntervals.push(interval);
    },
    clearStreaming() {
      this.streamingIntervals.forEach(interval => clearInterval(interval));
      this.streamingIntervals = [];
      this.isStreaming = false;
    },
    processMarkdown(text) {
      if (!text) return '';
      
      let processed = text
        // Convert [source] links to clipboard icon with punctuation
        .replace(/\[source\]\(([^)]+)\)([.,!?;:])/gi, '<a href="$1" target="_blank" rel="noopener noreferrer" class="inline text-blue-300 hover:text-blue-200 transition-colors mx-0.5" title="View source" style="display: inline; vertical-align: middle;"><span style="display: inline-flex; align-items: center; background: rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 2px 4px;"><svg style="width: 14px; height: 14px; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span></a>$2')
        // Convert remaining [source] links (without trailing punctuation)
        .replace(/\[source\]\(([^)]+)\)/gi, '<a href="$1" target="_blank" rel="noopener noreferrer" class="inline text-blue-300 hover:text-blue-200 transition-colors mx-0.5" title="View source" style="display: inline; vertical-align: middle;"><span style="display: inline-flex; align-items: center; background: rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 2px 4px;"><svg style="width: 14px; height: 14px; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span></a>')
        // Convert numbered citation links [1], [2], etc. with punctuation
        .replace(/\[(\d+|\*)\]\(([^)]+)\)([.,!?;:])/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="inline text-blue-300 hover:text-blue-200 transition-colors mx-0.5" title="View source" style="display: inline; vertical-align: middle;"><span style="display: inline-flex; align-items: center; background: rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 2px 4px;"><svg style="width: 14px; height: 14px; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span></a>$3')
        // Convert numbered citation links [1], [2], etc. without trailing punctuation
        .replace(/\[(\d+|\*)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="inline text-blue-300 hover:text-blue-200 transition-colors mx-0.5" title="View source" style="display: inline; vertical-align: middle;"><span style="display: inline-flex; align-items: center; background: rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 2px 4px;"><svg style="width: 14px; height: 14px; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span></a>')
        // Convert markdown links [text](url) to HTML links with spacing after punctuation
        .replace(/\[([^\]]+)\]\(([^)]+)\)([.,!?;:])/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-blue-300 hover:text-blue-200 underline">$1</a>$3 ')
        // Convert remaining markdown links (without trailing punctuation)
        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-blue-300 hover:text-blue-200 underline">$1</a> ')
        // Convert bold text **text** to HTML bold
        .replace(/\*\*([^*]+)\*\*/g, '<strong class="font-semibold">$1</strong>')
        // Add space after punctuation if followed by newline and capital letter (new sentence)
        .replace(/([.,!?;:])\n([A-Z])/g, '$1 $2')
        // Convert remaining single line breaks to <br> with spacing
        .replace(/\n/g, '<br class="mb-3">')
        // Style the cursor to make it blink
        .replace(/\|$/, '<span class="inline-block animate-pulse ml-0.5 font-bold">|</span>');
      
      // Wrap in a div for proper spacing
      processed = '<div class="space-y-2">' + processed + '</div>';
      
      return processed;
    }
  },
  emits: ['submit']
};
</script>

<style lang="scss" scoped>
.animate-fade-in-up {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.3s ease-out forwards;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Ensure backdrop blur works properly */
@supports (backdrop-filter: blur()) {
  .backdrop-blur {
    backdrop-filter: blur(12px);
  }
}

/* Hide browser default search input clear button */
.search-input-clean {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.search-input-clean::-webkit-search-cancel-button {
  display: none;
}

.search-input-clean::-webkit-search-decoration {
  display: none;
}

/* Live skeleton animations with contrasting directions */
.skeleton-wave {
  position: relative;
  overflow: hidden;
}

.skeleton-wave::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
  animation: skeleton-shimmer 2s infinite ease-in-out;
}

.skeleton-1::before { 
  animation-delay: 0s; 
}

@keyframes skeleton-shimmer {
  0% {
    left: -100%;
  }
  50% {
    left: 100%;
  }
  100% {
    left: 100%;
  }
}

/* Line clamp utility */
.line-clamp-5 {
  display: -webkit-box;
  -webkit-line-clamp: 5;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom prose styling for the answer content */
.prose p {
  margin-bottom: 0.5rem;
  font-size: 0.75rem;
  line-height: 1.5;
}

.prose p:last-child {
  margin-bottom: 0;
}

.prose strong {
  font-weight: 600;
  color: rgba(255, 255, 255, 0.95);
}

.prose a {
  color: rgb(147 197 253);
  text-decoration: underline;
  transition: color 0.2s ease;
}

.prose a:hover {
  color: rgb(196 181 253);
}

/* Custom scrollbar styling */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* For Firefox */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
}

/* Only apply hover zoom on devices with hover capability (not touch devices) */
@media (hover: hover) and (pointer: fine) {
  .hover-scale:hover:not(.answer-active) {
    transform: scale(1.05);
  }
}

/* Ensure no transform when answer is active */
.answer-active {
  transform: scale(1);
}
</style>
