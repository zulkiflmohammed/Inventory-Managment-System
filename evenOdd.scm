(define (even1? n)
    (cond ((= n 0) #t) 
        ((= n 1) #f)
        (else (even1? (- n 2))))) 
  
(define (odd1? n)
    (cond ((= n 1) #t) 
        ((= n 0) #f) 
        (else (odd1? (- n 2)))))