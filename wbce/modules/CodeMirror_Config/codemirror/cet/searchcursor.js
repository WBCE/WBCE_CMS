// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE
// SearchCursor for CodeMirror 5 — standalone (no external deps)
(function(mod){
  if(typeof exports=="object"&&typeof module=="object") mod(require("../../lib/codemirror"));
  else if(typeof define=="function"&&define.amd) define(["../../lib/codemirror"],mod);
  else mod(CodeMirror);
})(function(CodeMirror){
  "use strict";

  function SearchCursor(doc,query,pos,caseFold){
    this.atOccurrence=false; this.doc=doc;
    if(caseFold==null&&typeof query=="string") caseFold=false;
    pos=pos?doc.clipPos(pos):CodeMirror.Pos(0,0);
    this.pos={from:pos,to:pos};
    if(typeof query!="string"){
      query=new RegExp(query.source||query,"gm"+(caseFold?"i":""));
      this.matches=function(reverse,pos){
        if(reverse){
          var str=doc.getRange(CodeMirror.Pos(pos.line,0),pos);
          var m, line=pos.line;
          for(var ml=str.match(/\n/g),mi=0;mi<(ml?ml.length:0)+1;mi++){
            query.lastIndex=0;
            var start=mi?str.indexOf("\n",str.indexOf("\n")+1):0;
            var l=str.slice(start);
            while((m=query.exec(l))!=null){
              var from=CodeMirror.Pos(line-mi,start?m.index+start.length:m.index);
              var to=CodeMirror.Pos(from.line,from.ch+m[0].length);
              if(from.ch>=pos.ch) continue;
              return{from:from,to:to};
            }
          }
        } else {
          var line=pos.line,str=doc.getRange(pos,CodeMirror.Pos(pos.line+1,0));
          query.lastIndex=0;
          var m=query.exec(str);
          if(m){
            var from=CodeMirror.Pos(line,m.index);
            return{from:from,to:CodeMirror.Pos(from.line,from.ch+m[0].length)};
          }
        }
      };
    } else {
      var cs=caseFold?query.toLowerCase():query;
      this.matches=function(reverse,pos){
        var line=pos.line,str,start;
        if(reverse){
          str=doc.getLine(line).slice(0,pos.ch);
          var idx=(caseFold?str.toLowerCase():str).lastIndexOf(cs);
          if(idx>-1) return{from:CodeMirror.Pos(line,idx),to:CodeMirror.Pos(line,idx+cs.length)};
          for(var i=line-1;i>=0;i--){
            str=doc.getLine(i);
            var idx2=(caseFold?str.toLowerCase():str).lastIndexOf(cs);
            if(idx2>-1) return{from:CodeMirror.Pos(i,idx2),to:CodeMirror.Pos(i,idx2+cs.length)};
          }
        } else {
          str=doc.getLine(line).slice(pos.ch);
          var idx=(caseFold?str.toLowerCase():str).indexOf(cs);
          if(idx>-1) return{from:CodeMirror.Pos(line,pos.ch+idx),to:CodeMirror.Pos(line,pos.ch+idx+cs.length)};
          for(var i=line+1,n=doc.lineCount();i<n;i++){
            str=doc.getLine(i);
            var idx2=(caseFold?str.toLowerCase():str).indexOf(cs);
            if(idx2>-1) return{from:CodeMirror.Pos(i,idx2),to:CodeMirror.Pos(i,idx2+cs.length)};
          }
        }
      };
    }
  }

  SearchCursor.prototype={
    findNext:function(){return this.find(false);},
    findPrevious:function(){return this.find(true);},
    find:function(reverse){
      var result,pos=this.doc.clipPos(reverse?this.pos.from:this.pos.to);
      for(var i=0;i<2;++i){
        result=this.matches(reverse,pos);
        if(result){
          if(!result.from.line&&!result.from.ch&&!result.to.line&&!result.to.ch) return;
          this.pos=result; this.atOccurrence=true;
          return this.pos.match||true;
        }
        pos=reverse?CodeMirror.Pos(this.doc.lastLine()):CodeMirror.Pos(0,0);
      }
      this.atOccurrence=false; return false;
    },
    from:function(){if(this.atOccurrence) return this.pos.from;},
    to:function(){if(this.atOccurrence) return this.pos.to;},
    replace:function(text,origin){
      if(!this.atOccurrence) return;
      var lines=CodeMirror.splitLines(text);
      this.doc.replaceRange(lines,this.pos.from,this.pos.to,origin||"+input");
      this.pos.to=CodeMirror.Pos(this.pos.from.line+lines.length-1,
        lines[lines.length-1].length+(lines.length==1?this.pos.from.ch:0));
    }
  };
  CodeMirror.defineExtension("getSearchCursor",function(query,pos,caseFold){
    return new SearchCursor(this.doc,query,pos,caseFold);
  });
  CodeMirror.defineDocExtension("getSearchCursor",function(query,pos,caseFold){
    return new SearchCursor(this,query,pos,caseFold);
  });
  CodeMirror.defineExtension("selectMatches",function(query,caseFold){
    var ranges=[],next;
    var cur=this.getSearchCursor(parseQuery(query),this.getCursor("from"),caseFold);
    while(next=cur.findNext()){
      if(CodeMirror.cmpPos(cur.to(),this.getCursor("to"))>0) break;
      ranges.push({anchor:cur.from(),head:cur.to()});
    }
    if(ranges.length) this.setSelections(ranges,0);
  });
});
